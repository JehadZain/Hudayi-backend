<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppAdmin;
use App\Models\Infos\Status;
use App\Models\Morphs\Address;
use App\Models\Users\User;
use App\Repositories\Common\V1\AdminRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppAdminRepository extends AdminRepository implements IAppAdmin
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->appAdminWithUser()
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA,
                    null,
                    ['NO_DATA']
                );
            } else {
                throw new \Exception('SOMETHING_WENT_WRONG');
            }
        } catch (Exception $e) {
            return $this->setResponse(
                parent::FAILED,
                null,
                [$e->getMessage()]
            );
        }
    }

    public function appById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)
                ->appAdminWithUser()
                ->firstOrFail();

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function appGetAllUnassignedAdmins($params = null): object
    {
        try {
            $data = $this->builder
                ->whereDoesntHave('propertyAdmins')
                ->whereDoesntHave('branchAdmins')
                ->whereDoesntHave('organizationAdmins')
                ->appAdminWithUser()->get();
            if ($this->builder->count() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($this->builder->count() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
            } else {
                throw new Exception('SOMETHING_WENT_WRONG');
            }
        } catch (Exception $e) {
            return $this->setResponse(
                parent::FAILED,
                null,
                [$e->getMessage()]
            );
        }
    }

    public function appCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $user = $this->createObject($object['user'], $this->associate);

            if (! empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (isset($object['user']['image'])) {
                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path.'/'.$filename;
            }

            unset($object['user']);

            $admin = $this->createObject($object, $this->model, false);
            $admin->user()->associate($user);
            $user->save();
            $admin->save();

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $admin,
                ['Admin Created Successfully']

            );
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            if (Str::contains($errorMessage, 'users_username_unique')) {
                $errorMessage = 'Username already exists.';
            } elseif (Str::contains($errorMessage, 'users_email_unique')) {
                $errorMessage = 'Email address is already in use.';
            } elseif (Str::contains($errorMessage, 'users_identity_number_unique')) {
                $errorMessage = 'Identity number is already in use.';
            } else {
                $errorMessage = 'Something went wrong';
            }

            return $this->setResponse(
                parent::FAILED,
                null,
                ['Error' => $errorMessage]
            );
        }
    }

    public function appUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $admin = $this->builder->whereId($id)->firstOrFail();
            $user = $admin->user;
            $user->fill($object['user']);

            //            $user->property_id = $object['user']['property_id'];
            //            $user->email = $object['user']['email'];
            //            $user->first_name = $object['user']['first_name'];
            //            $user->last_name = $object['user']['last_name'];
            //            $user->username = $object['user']['username'];
            //            $user->identity_number = $object['user']['identity_number'];
            //            $user->phone = $object['user']['phone'];
            //            $user->gender = $object['user']['gender'];
            //            $user->birth_date = $object['user']['birth_date'];
            //            $user->birth_place = $object['user']['birth_place'];
            //            $user->father_name = $object['user']['father_name'];
            //            $user->mother_name = $object['user']['mother_name'];
            //            $user->qr_code = $object['user']['qr_code'];
            //            $user->blood_type = $object['user']['blood_type'];
            //            $user->note = $object['user']['note'];
            //            $user->current_address = $object['user']['current_address'];
            //            $user->is_has_disease = $object['user']['is_has_disease'];
            //            $user->disease_name = $object['user']['current_address'];
            //            $user->is_has_treatment = $object['user']['is_has_treatment'];
            //            $user->treatment_name = $object['user']['treatment_name'];
            //            $user->are_there_disease_in_family = $object['user']['are_there_disease_in_family'];
            //            $user->family_disease_note = $object['user']['family_disease_note'];
            //            $user->status = $object['user']['status'];

            if (! empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (! empty($object['user']['username'])) {
                $user->username = $object['user']['username'];
            }

            // Check if the image is present in the request
            if (! empty($object['user']['image'])) {
                $db_user = User::find($id);

                // Check if there's an old image
                if (!isset($user->image)) {

                    // Delete the old image file
                    $oldImagePath = public_path($db_user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                $user = User::find($id);
                // Check if there's an old image
                if (!empty($user->image)) {
                    // Delete the old image file
                    $oldImagePath = public_path($user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store the path link in the database
                $user->image = $path.'/'.$filename;
            }

            $user->save();
            $admin->fill($object);
            $admin->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $admin,
                ['Admin Updated Successfully']
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            if (Str::contains($errorMessage, 'users_username_unique')) {
                $errorMessage = 'Username already exists.';
            } elseif (Str::contains($errorMessage, 'users_email_unique')) {
                $errorMessage = 'Email address is already in use.';
            } elseif (Str::contains($errorMessage, 'users_identity_number_unique')) {
                $errorMessage = 'Identity number is already in use.';
            } else {
                $errorMessage = 'Something went wrong';
            }

            return $this->setResponse(
                parent::FAILED,
                null,
                ['Error' => $errorMessage]
            );
        }
    }

    public function appDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->delete();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                [$e->getMessage()]
            );
        }
    }
}
