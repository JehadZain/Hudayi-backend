<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileCalendar;
use App\Repositories\Common\V1\CalendarRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileCalendarRepository extends CalendarRepository implements IMobileCalendar
{
    public function mobileAll($params = null): object
    {
        try {

            $data = $this->builder->select(
                'id',
                'class_room_id',
                'day_name',
                'subject_name',
                'start_at',
                'end_at',
            )->orderBy($params->orderBy, $params->direction)
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
            $data = $this->builder->whereId($id)
                ->select(
                    'id',
                    'class_room_id',
                    'day_name',
                    'subject_name',
                    'start_at',
                    'end_at',
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $obj = new $this->model;
            $obj->class_room_id = $object['class_room_id'];
            $obj->day_name = $object['day_name'];
            $obj->subject_name = $object['subject_name'];
            $obj->start_at = $object['start_at'];
            $obj->end_at = $object['end_at'];
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
            $obj->class_room_id = $object['class_room_id'];
            $obj->day_name = $object['day_name'];
            $obj->subject_name = $object['subject_name'];
            $obj->start_at = $object['start_at'];
            $obj->end_at = $object['end_at'];
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
