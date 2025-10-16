<?php

namespace App\Repositories\Mobile\V1;

use App\Http\Helpers\ImageHelper;
use App\Interfaces\Mobile\V1\IMobileUser;
use App\Repositories\Common\V1\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MobileUserRepository extends UserRepository implements IMobileUser
{
    public function mobileAll($params = null): object
    {
        try {

            $this->builder = $this->builder->select(
                'id',
                'property_id',
                'email',
                'first_name',
                'last_name',
                'username',
                'identity_number',
                'phone',
                'gender',
                'birth_date',
                'birth_place',
                'father_name',
                'mother_name',
                'qr_code',
                'blood_type',
                'note',
                'current_address',
                'is_has_disease',
                'disease_name',
                'is_has_treatment',
                'treatment_name',
                'are_there_disease_in_family',
                'family_disease_note',
                'status',
                'image',
                'is_approved'
            )->where('is_approved', true);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
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

    public function mobileAllUsersNotApproved($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'property_id',
                'email',
                'first_name',
                'last_name',
                'username',
                'identity_number',
                'phone',
                'gender',
                'birth_date',
                'birth_place',
                'father_name',
                'mother_name',
                'qr_code',
                'blood_type',
                'note',
                'current_address',
                'is_has_disease',
                'disease_name',
                'is_has_treatment',
                'treatment_name',
                'are_there_disease_in_family',
                'family_disease_note',
                'status',
                'image',
                'is_approved'
            )->where('is_approved', false);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
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
            $user = $this->builder->whereId($id)->select(
                'id',
                'property_id',
                'email',
                'first_name',
                'last_name',
                'username',
                'identity_number',
                'phone',
                'gender',
                'birth_date',
                'birth_place',
                'father_name',
                'mother_name',
                'qr_code',
                'blood_type',
                'note',
                'current_address',
                'is_has_disease',
                'disease_name',
                'is_has_treatment',
                'treatment_name',
                'are_there_disease_in_family',
                'family_disease_note',
                'status',
                'image'
            )->firstOrFail();

            $is_student = $user->students ? $user->students->isNotEmpty() : false;
            $is_teacher = $user->teachers ? $user->teachers->isNotEmpty() : false;
            $is_admin = $user->admins ? $user->admins->isNotEmpty() : false;

            $role = null;
            if ($is_student) {
                $role = 'student';
            } elseif ($is_teacher) {
                $role = 'teacher';
            } elseif ($is_admin) {
                $role = 'admin';
            }

            $data = [
                'user' => $user,
                'role' => $role,
            ];

            $response = response()->json($data);

            $data = $response->getData(true);

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );
        } catch (\Exception $e) {

            return $this->setResponse(
                parent::NO_DATA,
                null,
                [$e->getMessage()]
            );
        }
    }

    public function uploadImage($imageUrl)
    {
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
        $this->image = $path.'/'.$filename;
    }

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();

            $obj = new $this->model;
            $obj->fill($object->toArray());

            if (isset($object['image'])) {
                //                $obj->uploadImage($object['image']);

                $imageUrl = $object['image'];
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
                $this->image = $path.'/'.$filename;
            }

            $obj->save();
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

    //    public function mobileCreateObject($object): object
    //    {
    //        try {
    //            DB::beginTransaction();
    //
    //            $obj = new $this->model;
    //            $obj->fill($object->toArray());
    //
    ////            $obj->email = $object['email'];
    ////            $obj->password = $object['password'];
    ////            $obj->first_name = $object['first_name'];
    ////            $obj->last_name = $object['last_name'];
    ////            $obj->username = $object['username'];
    ////            $obj->identity_number = $object['identity_number'];
    ////            $obj->phone = $object['phone'];
    ////            $obj->gender = $object['gender'];
    ////            $obj->birth_date = $object['birth_date'];
    ////            $obj->birth_place = $object['birth_place'];
    ////            $obj->father_name = $object['father_name'];
    ////            $obj->mother_name = $object['mother_name'];
    ////            $obj->qr_code = $object['qr_code'];
    ////            $obj->blood_type = $object['blood_type'];
    ////            $obj->note = $object['note'];
    ////
    ////            $obj->current_address = $object['current_address'];
    ////            $obj->is_has_disease = $object['is_has_disease'];
    ////            $obj->disease_name = $object['current_address'];
    ////            $obj->is_has_treatment = $object['is_has_treatment'];
    ////            $obj->treatment_name = $object['treatment_name'];
    ////            $obj->are_there_disease_in_family = $object['are_there_disease_in_family'];
    ////            $obj->family_disease_note = $object['family_disease_note'];
    ////            $obj->status = $object['status'];
    //
    //            // Check if the image is present in the request
    ////            if ($object->hasFile('image')) {
    ////                $image = $object->file('image');
    ////                $path = Storage::putFile('public/images/users', $image);
    ////                $obj->image = $path;
    ////            }
    ////            if ($object->hasFile('image')) {
    ////                $image = $object->file('image');
    ////                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
    ////                $image->storeAs('public/images/users', $filename);
    ////                $obj->image = $filename;
    ////            }
    //
    //            // Check if the image is present in the request
    ////            if (isset($object['image'])) {
    ////                $base64 = $object['image'];
    ////                $path = 'store/properties';
    ////                $obj->image = ImageHelper::uploadImage($base64, $path);
    ////            }
    //            if (isset($object['image'])) {
    //                $imageUrl = $object['image'];
    //                $imageData = file_get_contents($imageUrl);
    //                $base64 = base64_encode($imageData);
    //                $path = 'storage/images/users';
    //
    //                // Generate a unique filename for the image
    //                $filename = uniqid() . '.' . 'png';
    //
    //                // Build the full path to the file
    //                $fullPath = public_path($path . '/' . $filename);
    //
    //                // Save the base64-encoded image data to disk
    //                file_put_contents($fullPath, base64_decode($base64));
    //
    //                // Store the path link in the database
    //                $obj->image = $path . '/' . $filename;
    //            }
    //
    //
    //            $obj->save();
    //            DB::commit();
    //            return $this->setResponse(
    //                parent::SUCCESS,
    //                $obj,
    //            );
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return $this->setResponse(
    //                parent::FAILED,
    //                null,
    //                [$e->getMessage()]
    //            );
    //        }
    //    }

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            //            $obj->email = $object['email'];
            //            $obj->password = $object['password'];
            //            $obj->first_name = $object['first_name'];
            //            $obj->last_name = $object['last_name'];
            //            $obj->username = $object['username'];
            //            $obj->identity_number = $object['identity_number'];
            //            $obj->phone = $object['phone'];
            //            $obj->gender = $object['gender'];
            //            $obj->birth_date = $object['birth_date'];
            //            $obj->birth_place = $object['birth_place'];
            //            $obj->father_name = $object['father_name'];
            //            $obj->mother_name = $object['mother_name'];
            //            $obj->qr_code = $object['qr_code'];
            //            $obj->blood_type = $object['blood_type'];
            //            $obj->note = $object['note'];
            //
            //            $obj->current_address = $object['current_address'];
            //            $obj->is_has_disease = $object['is_has_disease'];
            //            $obj->disease_name = $object['current_address'];
            //            $obj->is_has_treatment = $object['is_has_treatment'];
            //            $obj->treatment_name = $object['treatment_name'];
            //            $obj->are_there_disease_in_family = $object['are_there_disease_in_family'];
            //            $obj->family_disease_note = $object['family_disease_note'];
            //            $obj->status = $object['status'];

            $obj->fill($object->toArray());

            if (isset($object['image'])) {
                $imageUrl = $object['image'];
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
                $obj->image = $path.'/'.$filename;
            }

            $obj->save();
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

    public function mobileTransferObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            //            $obj->email = $object['email'];
            //            $obj->password = $object['password'];
            //            $obj->first_name = $object['first_name'];
            //            $obj->last_name = $object['last_name'];
            //            $obj->username = $object['username'];
            //            $obj->identity_number = $object['identity_number'];
            //            $obj->phone = $object['phone'];
            //            $obj->gender = $object['gender'];
            //            $obj->birth_date = $object['birth_date'];
            //            $obj->birth_place = $object['birth_place'];
            //            $obj->father_name = $object['father_name'];
            //            $obj->mother_name = $object['mother_name'];
            //            $obj->qr_code = $object['qr_code'];
            //            $obj->blood_type = $object['blood_type'];
            //            $obj->note = $object['note'];
            //
            //            $obj->current_address = $object['current_address'];
            //            $obj->is_has_disease = $object['is_has_disease'];
            //            $obj->disease_name = $object['current_address'];
            //            $obj->is_has_treatment = $object['is_has_treatment'];
            //            $obj->treatment_name = $object['treatment_name'];
            //            $obj->are_there_disease_in_family = $object['are_there_disease_in_family'];
            //            $obj->family_disease_note = $object['family_disease_note'];
            //            $obj->status = $object['status'];

            $obj->fill($object->toArray());

            if (isset($object['image'])) {
                $imageUrl = $object['image'];
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
                $obj->image = $path.'/'.$filename;
            }

            if ($obj->students()) {
                dd('student', $obj->classRooms);
            }

            if ($obj->teachers()) {
                dd('teachers', $obj->classRooms);

            }

            if ($obj->admins()) {
                dd('admins');
            }

            $obj->save();
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
