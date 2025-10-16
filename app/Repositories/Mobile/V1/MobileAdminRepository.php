<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileAdmin;
use App\Models\Infos\Status;
use App\Models\Morphs\Address;
use App\Models\Users\Admin;
use App\Models\Users\User;
use App\Repositories\Common\V1\AdminRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileAdminRepository extends AdminRepository implements IMobileAdmin
{
    public function mobileAll($params = null): object
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

    public function mobileById(string $id): object
    {
        try {
            $user = $this->builder->whereId($id)
                ->appAdminWithUser()
                ->firstOrFail();

            $admin = Admin::whereId($id)->first();
            $propertyAdmin = $admin?->propertyAdmins()->first();
            $branchAdmin = $admin?->branchAdmins()->first();
            $orgAdmin = $admin?->organizationAdmins()->first();

            $data = [
                'user' => $user,
                'role' => 'Unassigned',
                'Belongs_to' => [
                    'property_id' => null,
                    'branch_id' => null,
                    'organization_id' => null,
                ]
            ];

            if ($propertyAdmin) {
                $branch = $propertyAdmin?->property?->branch()->first();
                $org = $branch?->organization;

                $data['role'] = 'property_admin';

                $data['Belongs_to'] = [
                    'property_name' => $propertyAdmin?->property?->name,
                    'branch_name' => $branch?->name,
                    'organization_name' => $org?->name,
                ];
            }

            if ($branchAdmin) {
                $org = $branchAdmin?->branch?->organization()->first();
                $data['role'] = 'branch_admin';
                $data['Belongs_to'] = [
                    'property_name' => null,
                    'branch_name' => $branchAdmin?->branch?->name,
                    'organization_name' => $org?->name,
                ];
            }

            if ($orgAdmin) {
                $org = $orgAdmin?->organization;

//                dd($org);
                $data['role'] = 'org_admin';
                $data['Belongs_to'] = [
                    'property_name' => null,
                    'branch_name' => null,
                    'organization_name' => $org?->name,
                ];
            }

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


    public function mobileGetAllUnassignedAdmins($params = null): object
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $user = $this->createObject($object['user'], $this->associate);

            if (!empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (isset($object['user']['image'])) {
                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path . '/' . $filename;
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

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $admin = $this->builder->whereId($id)->firstOrFail();
            $user = $admin->user;
            $user->fill($object['user']);

            if (!empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (!empty($object['user']['username'])) {
                $user->username = $object['user']['username'];
            }

            // Check if the image is present in the request
            if (!empty($object['user']['image'])) {
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
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

//                $user = User::find($id);
//                // Check if there's an old image
//                if (!empty($user->image)) {
//                    // Delete the old image file
//                    $oldImagePath = public_path($user->image);
//                    if (file_exists($oldImagePath)) {
//                        unlink($oldImagePath);
//                    }
//                }

                // Store the path link in the database
                $user->image = $path . '/' . $filename;
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

    public function mobileDeleteObject(string $id): object
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
