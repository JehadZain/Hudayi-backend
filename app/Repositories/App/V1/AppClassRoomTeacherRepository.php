<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppClassRoomTeacher;
use App\Repositories\Common\V1\ClassRoomTeacherRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppClassRoomTeacherRepository extends ClassRoomTeacherRepository implements IAppClassRoomTeacher
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'class_room_id',
                'teacher_id',
                'joined_at',
                'left_at',
            );

            $filter = $this->filter($params);
            if ($this->builder->count() > 0) {
                $data = $filter->paginate();

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

    public function appById(string $id): object
    {
        try {
            $data = $this->builder->where('class_room_id', $id)->select(
                'id',
                'class_room_id',
                'teacher_id',
                'joined_at',
                'left_at',
            )
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

    public function appCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $obj = new $this->model;
            $obj->class_room_id = $object['class_room_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->joined_at = $object['joined_at'];
            $obj->left_at = $object['left_at'];
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

    public function appUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->class_room_id = $object['class_room_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->joined_at = $object['joined_at'];
            $obj->left_at = $object['left_at'];
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

    public function appDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->left_at = now();
            $obj->save();

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
