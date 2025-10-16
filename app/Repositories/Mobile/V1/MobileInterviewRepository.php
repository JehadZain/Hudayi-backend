<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileInterview;
use App\Repositories\Common\V1\InterviewRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileInterviewRepository extends InterviewRepository implements IMobileInterview
{
    public function mobileAll($params = null): object
    {
        try {
            $data = $this->builder->select(
                'id',
                'name',
                'event_place',
                'date',
                'goal',
                'type',
                'comment',
                'student_id',
                'teacher_id',
                'image',
                'score'
            )->with('teacher.user:id,first_name,last_name,image')
                ->with('student.user:id,first_name,last_name,image')
                ->orderBy($params->orderBy, $params->direction)
                ->get();

            //            $filter = $this->filter($params);
            if ($this->builder->count() > 0) {
                //                $data = $filter->paginate();
                return $this->setResponse(parent::SUCCESS, $data);
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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'name',
                'event_place',
                'date',
                'goal',
                'comment',
                'type',
                'student_id',
                'teacher_id',
                'image',
                'score'
            )->with('teacher.user:id,first_name,last_name,image')
                ->with('student.user:id,first_name,last_name,image')
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
            $obj->name = $object['name'];
            $obj->event_place = $object['event_place'];
            $obj->date = $object['date'];
            $obj->goal = $object['goal'];
            $obj->comment = $object['comment'];
            $obj->type = $object['type'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->score = $object['score'];

            // If an image is included in the create data,
            // save it to the server and set the object's image attribute
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/books-interview';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

//            if ($this->builder->where('name', $obj->name)->exists()) {
//                DB::rollBack();
//
//                return $this->setResponse(
//                    parent::FAILED,
//                    [],
//                    ['Object name is already exist']
//                );
//            }

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

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->name = $object['name'];
            $obj->event_place = $object['event_place'];
            $obj->date = $object['date'];
            $obj->goal = $object['goal'];
            $obj->comment = $object['comment'];
            $obj->type = $object['type'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->score = $object['score'];

            // Check if a new image is included in the update data
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/books-interview';

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
