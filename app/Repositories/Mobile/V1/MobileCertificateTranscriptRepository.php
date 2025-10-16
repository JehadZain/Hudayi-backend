<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileCertificateTranscript;
use App\Repositories\Common\V1\CertificateTranscriptRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileCertificateTranscriptRepository extends CertificateTranscriptRepository implements IMobileCertificateTranscript
{
    public function mobileAll($params): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'certification_id',
                'subject_id',
                'max',
                'points',
                'percentage',
                'grade_name',
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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'certification_id',
                'subject_id',
                'max',
                'points',
                'percentage',
                'grade_name',
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $obj = new $this->model;
            $obj->certification_id = $object['certification_id'];
            $obj->subject_id = $object['subject_id'];
            $obj->max = $object['max'];
            $obj->points = $object['points'];
            $obj->percentage = $object['percentage'];
            $obj->grade_name = $object['grade_name'];

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
            $obj->certification_id = $object['certification_id'];
            $obj->subject_id = $object['subject_id'];
            $obj->max = $object['max'];
            $obj->points = $object['points'];
            $obj->percentage = $object['percentage'];
            $obj->grade_name = $object['grade_name'];
            $obj->save();
            DB::commit();

            return $obj;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
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
