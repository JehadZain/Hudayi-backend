<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppPatientTreatment;
use App\Repositories\Common\V1\PatientTreatmentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppPatientTreatmentRepository extends PatientTreatmentRepository implements IAppPatientTreatment
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'patient_id',
                'treatment_name',
                'treatment_cost',
                'availability',
                'schedule',
            )->with('patient:id,user_id,disease_name,diagnosis_date,medical_report_url,medical_report_image')
                ->with('patient.user:id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,image,father_name,mother_name,qr_code,blood_type,note');

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
            $data = $this->builder->whereId($id)->select(
                'id',
                'patient_id',
                'treatment_name',
                'treatment_cost',
                'availability',
                'schedule',
            )->with('patient:id,user_id,disease_name,diagnosis_date,medical_report_url,medical_report_image')
                ->with('patient.user:id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,image,father_name,mother_name,qr_code,blood_type,note')
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
            $obj->patient_id = $object['patient_id'];
            $obj->treatment_name = $object['treatment_name'];
            $obj->treatment_cost = $object['treatment_cost'];
            $obj->availability = $object['availability'];
            $obj->schedule = $object['schedule'];
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
            $obj->patient_id = $object['patient_id'];
            $obj->treatment_name = $object['treatment_name'];
            $obj->treatment_cost = $object['treatment_cost'];
            $obj->availability = $object['availability'];
            $obj->schedule = $object['schedule'];
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
