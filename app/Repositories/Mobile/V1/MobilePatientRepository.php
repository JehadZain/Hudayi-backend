<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobilePatient;
use App\Repositories\Common\V1\PatientRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobilePatientRepository extends PatientRepository implements IMobilePatient
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'user_id',
                'disease_name',
                'diagnosis_date',
                'medical_report_url',
                'medical_report_image',
            )->with('user:id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,image,father_name,mother_name,qr_code,blood_type,note');

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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'user_id',
                'disease_name',
                'diagnosis_date',
                'medical_report_url',
                'medical_report_image',
            )->with('user:id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,image,father_name,mother_name,qr_code,blood_type,note')
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
            $obj->user_id = $object['user_id'];
            $obj->disease_name = $object['disease_name'];
            $obj->diagnosis_date = $object['diagnosis_date'];
            $obj->medical_report_url = $object['medical_report_url'];
            $obj->medical_report_image = $object['medical_report_image'];
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
            $obj->user_id = $object['user_id'];
            $obj->disease_name = $object['disease_name'];
            $obj->diagnosis_date = $object['diagnosis_date'];
            $obj->medical_report_url = $object['medical_report_url'];
            $obj->medical_report_image = $object['medical_report_image'];
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
