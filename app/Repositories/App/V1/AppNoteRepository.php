<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppNote;
use App\Repositories\Common\V1\NoteRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppNoteRepository extends NoteRepository implements IAppNote
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'date',
                'teacher_content',
                'admin_content',
                'student_id',
                'teacher_id',
                'admin_id'
            )->with('teacher.user:id,first_name,last_name,image')
                ->with('admin.user:id,first_name,last_name,image')
                ->with('student.user:id,first_name,last_name,image')
                ->orderBy($params->orderBy, $params->direction);

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
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::FAILED,
                null,
                [$e->getMessage()]
            );
        }
    }

    public function appGetAllNotesByTeacherId(string $id, $params = null): object
    {
        try {
            $data = $this->builder->select(
                'id',
                'date',
                'teacher_content',
                'admin_content',
                'student_id',
                'teacher_id',
                'admin_id'
            )->with('teacher.user:id,first_name,last_name,image')
                ->with('admin.user:id,first_name,last_name,image')
                ->with('student.user:id,first_name,last_name,image')
                ->where('teacher_id', $id); // Filter by teacher_id

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

    public function appById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'date',
                'teacher_content',
                'admin_content',
                'student_id',
                'teacher_id',
                'admin_id'
            )->with('teacher.user:id,first_name,last_name,image')
                ->with('admin.user:id,first_name,last_name,image')
                ->with('student.user:id,first_name,last_name,image')->firstOrFail();

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
            $obj->date = $object['date'];
            $obj->admin_content = $object['admin_content'];
            $obj->teacher_content = $object['teacher_content'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->admin_id = $object['admin_id'];
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
            $obj->date = $object['date'];
            $obj->admin_content = $object['admin_content'];
            $obj->teacher_content = $object['teacher_content'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->admin_id = $object['admin_id'];
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
