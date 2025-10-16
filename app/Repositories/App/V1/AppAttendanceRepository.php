<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppAttendance;
use App\Repositories\Common\V1\AttendanceRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppAttendanceRepository extends AttendanceRepository implements IAppAttendance
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'attendanceable_type',
                'attendanceable_id',
                'session_id',
                'date',
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
                'attendanceable_type',
                'attendanceable_id',
                'session_id',
                'date',
            )->firstOrFail();

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
            $obj->attendanceable_type = $object['attendanceable_type'];
            $obj->attendanceable_id = $object['attendanceable_id'];
            $obj->session_id = $object['session_id'];
            $obj->date = $object['date'];
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
            $obj->attendanceable_type = $object['attendanceable_type'];
            $obj->attendanceable_id = $object['attendanceable_id'];
            $obj->session_id = $object['session_id'];
            $obj->date = $object['date'];
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
