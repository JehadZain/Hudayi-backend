<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppOrganization;
use App\Repositories\Common\V1\OrganizationRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppOrganizationRepository extends OrganizationRepository implements IAppOrganization
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'logo',
                'location',
                'capacity',
                'website',
                'description',
            )->with('branches')
                ->with('organizationAdmins.admin.user:id,first_name,last_name,image');

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
                'name',
                'logo',
                'location',
                'capacity',
                'website',
                'description',
            )->with('branches')
                ->with('organizationAdmins.admin.user:id,first_name,last_name,image')
                ->firstOrFail();

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );

            //            return $this->setResponse(
            //                parent::SUCCESS,
            //                $data,
            //            );
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
            $obj->name = $object['name'];
            $obj->logo = $object['logo'];
            $obj->location = $object['location'];
            $obj->capacity = $object['capacity'];
            $obj->website = $object['website'];
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

    public function appUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->name = $object['name'];
            $obj->logo = $object['logo'];
            $obj->location = $object['location'];
            $obj->capacity = $object['capacity'];
            $obj->website = $object['website'];
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
            //            dd($obj->branches); // inspect the related Branch models before the delete

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
