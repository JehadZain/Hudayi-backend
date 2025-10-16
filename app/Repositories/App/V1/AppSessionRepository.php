<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppSession;
use App\Repositories\Common\V1\SessionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppSessionRepository extends SessionRepository implements IAppSession
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'description',
                'date',
                'start_at',
                'duration',
                'teacher_id',
                'class_room_id',
                'subject_name',
                'place',
                'type'
            )->with('teacher.user:id,first_name,last_name,gender')
                ->with('classRoom:id,name')
                ->with('sessionAttendances.student.user:id,first_name,last_name,gender')
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

    public function appGetAllSessionByTeacherId(string $id, $params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'description',
                'date',
                'start_at',
                'duration',
                'teacher_id',
                'class_room_id',
                'subject_name',
                'place',
                'type'
            )->with('teacher.user:id,first_name,last_name,gender')
                ->with('classRoom:id,name')
                ->with('sessionAttendances.student.user:id,first_name,last_name,gender')
                ->where('teacher_id', $id);

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
            $data = $this->builder->whereId($id)->select(
                'id',
                'name',
                'description',
                'date',
                'start_at',
                'duration',
                'teacher_id',
                'class_room_id',
                'subject_name',
                'place',
                'type'
            )->with('teacher.user:id,first_name,last_name,gender')
                ->with('classRoom:id,name')
                ->with('sessionAttendances.student.user:id,first_name,last_name,gender')
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
            $obj->name = $object['name'];
            $obj->description = $object['description'];
            $obj->date = $object['date'];
            $obj->start_at = $object['start_at'];
            $obj->duration = $object['duration'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->class_room_id = $object['class_room_id'];
            $obj->subject_name = $object['subject_name'];
            $obj->place = $object['place'];
            $obj->type = $object['type'];
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
            $obj->name = $object['name'];
            $obj->description = $object['description'];
            $obj->date = $object['date'];
            $obj->start_at = $object['start_at'];
            $obj->duration = $object['duration'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->class_room_id = $object['class_room_id'];
            $obj->subject_name = $object['subject_name'];
            $obj->place = $object['place'];
            $obj->type = $object['type'];

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

            // Delete all related attendance records
            $obj->sessionAttendances()->delete();

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
