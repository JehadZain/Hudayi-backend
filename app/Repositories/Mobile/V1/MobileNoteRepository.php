<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileNote;
use App\Repositories\Common\V1\NoteRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileNoteRepository extends NoteRepository implements IMobileNote
{
    public function mobileAll($params = null): object
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

    public function mobileCreateObject($object): object
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

    public function mobileUpdateObject($object, $id): object
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
