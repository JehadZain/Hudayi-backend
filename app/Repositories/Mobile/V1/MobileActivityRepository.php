<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileActivity;
use App\Repositories\Common\V1\ActivityRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MobileActivityRepository extends ActivityRepository implements IMobileActivity
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->appActivity();

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
        } catch (\Exception $e) {
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
            $data = $this->builder->whereId($id)
                ->appActivity()
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $obj = new $this->model;
            $obj->class_room_id = $object['class_room_id'];
            $obj->activity_type_id = $object['activity_type_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->name = $object['name'];
            $obj->place = $object['place'];
            $obj->cost = $object['cost'];
            $obj->result = $object['result'];
            $obj->note = $object['note'];
            $obj->start_datetime = $object['start_datetime'];
            $obj->end_datetime = $object['end_datetime'];

            // Check if the image is present in the request
            //            if ($object->hasFile('image')) {
            //                $image = $object->file('image');
            //                $path = Storage::putFile('public/images/activities', $image);
            //                $obj->image = $path;
            //            }

            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/activities';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Check if there's an old image
                if (!empty($obj->image)) {
                    // Delete the old image file
                    $oldImagePath = public_path($obj->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

            $obj->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['Activity Created Successfully'],
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                ["Couldn't Create Activity"],
            );
        }
    }

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->class_room_id = $object['class_room_id'];
            $obj->activity_type_id = $object['activity_type_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->name = $object['name'];
            $obj->place = $object['place'];
            $obj->cost = $object['cost'];
            $obj->result = $object['result'];
            $obj->note = $object['note'];
            $obj->start_datetime = $object['start_datetime'];
            $obj->end_datetime = $object['end_datetime'];

            // Check if the image is present in the request
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/activities';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Check if there's an old image
                if (!empty($obj->image)) {
                    // Delete the old image file
                    $oldImagePath = public_path($obj->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }


                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

            $obj->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['Activity Updated Successfully'],
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                ["Couldn't Update Activity"],
//                [$e->getMessage()]
            );
        }
    }

    public function mobileDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->participants()->where('activity_id', $id)->delete();
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
