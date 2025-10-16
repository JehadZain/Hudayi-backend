<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppSchool;
use App\Repositories\Common\V1\SchoolRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppSchoolRepository extends SchoolRepository implements IAppSchool
{
    public function appAll($params = null): object
    {
        try {

            $this->builder = $this->builder->select(
                'id',
            )->with('property')
                ->with('contacts');

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
            )->with('property')
                ->with('contacts')
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
            $obj->save();

            $property = $this->createObject($object['property'], $this->morphTo, false);
            $obj->property()->save($property);
            $obj->property = $property;
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
            $obj->property->capacity = $object['property']['capacity'];
            $obj->property->name = $object['property']['name'];
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

            $obj = $this->builder->whereId($id)->with('property:id')->firstOrFail();
            $property = $this->morphTo::whereId($obj->property->id)->firstOrFail();
            $property->delete();
            $obj->delete();

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                [$obj, $property],
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
