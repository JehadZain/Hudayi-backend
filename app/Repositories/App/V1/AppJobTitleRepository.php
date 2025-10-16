<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppJobTitle;
use App\Repositories\Common\V1\JobTitleRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppJobTitleRepository extends JobTitleRepository implements IAppJobTitle
{
    public function appAll($params = null): object
    {
        try {

            $this->builder = $this->builder->appAdminJobTitle();
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
            $data = $this->builder->whereId($id)
                ->appAdminJobTitle()
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
            $obj = $this->createObject(
                $object,
                $this->model,
                false
            );
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
            $obj->admin_id = $object['admin_id'];
            $obj->name = $object['name'];
            $obj->description = $object['description'];
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
