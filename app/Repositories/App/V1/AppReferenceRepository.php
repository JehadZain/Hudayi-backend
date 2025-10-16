<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppReference;
use App\Repositories\Common\V1\ReferenceRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppReferenceRepository extends ReferenceRepository implements IAppReference
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'referenceable_type',
                'referenceable_id',
                'referenced_by',
                'description',
                'jop_title',
                'letter_url',
            );

            $filter = $this->filter($params);
            if ($this->builder->count() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $filter->paginate()
                );
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
                'referenceable_type',
                'referenceable_id',
                'referenced_by',
                'description',
                'jop_title',
                'letter_url',
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
            $obj->referenceable_type = $object['referenceable_type'];
            $obj->referenceable_id = $object['referenceable_id'];
            $obj->referenced_by = $object['referenced_by'];
            $obj->jop_title = $object['jop_title'];
            $obj->letter_url = $object['letter_url'];
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
            $obj->referenceable_type = $object['referenceable_type'];
            $obj->referenceable_id = $object['referenceable_id'];
            $obj->referenced_by = $object['referenced_by'];
            $obj->jop_title = $object['jop_title'];
            $obj->letter_url = $object['letter_url'];
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
