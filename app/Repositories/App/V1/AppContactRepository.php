<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppContact;
use App\Repositories\Common\V1\ContactRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppContactRepository extends ContactRepository implements IAppContact
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'contactable_type',
                'contactable_id',
                'type',
                'label',
                'value',
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
            $data = $this->builder->whereId($id)->select(
                'id',
                'contactable_type',
                'contactable_id',
                'type',
                'label',
                'value',
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
            $obj->contactable_type = $object['contactable_type'];
            $obj->contactable_id = $object['contactable_id'];
            $obj->type = $object['type'];
            $obj->label = $object['label'];
            $obj->value = $object['value'];
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
            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->contactable_type = $object['contactable_type'];
            $obj->contactable_id = $object['contactable_id'];
            $obj->type = $object['type'];
            $obj->label = $object['label'];
            $obj->value = $object['value'];
            $obj->save();

            return $obj;
        } catch (\Exception $e) {
            dd($e->getMessage());
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
