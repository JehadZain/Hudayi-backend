<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppBranch;
use App\Repositories\Common\V1\BranchRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppBranchRepository extends BranchRepository implements IAppBranch
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
            )->with('properties')
                ->with('branchAdmins.admin.user:id,first_name,last_name,image')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA,
                    null,
                    ['NO_DATA']
                );
            } else {
                throw new \Exception('SOMETHING_WENT_WRONG');
            }
        } catch (Exception $e) {
            return $this->setResponse(
                parent::FAILED,
                null,
                ['Could Not Find Data']
            );
        }
    }

    public function appById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)
                ->with('properties')
                ->with('branchAdmins.admin.user:id,first_name,last_name,image')
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
            $obj->name = $object['name'];
            $obj->organization_id = $object['organization_id'];
            $obj->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
            );
        } catch (Exception $e) {
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
            $obj->organization_id = $object['organization_id'];

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

            $properties = $obj->properties;
            foreach ($properties as $property) {
                $property?->propertyAdmins()->delete();

                $grades = $property->grades;
                foreach ($grades as $grade) {
                    $grade->classRooms()->delete();

                    $classRooms = $grade->classRooms;
                    foreach ($classRooms as $classRoom) {
                        $classRoom->sessions()->delete();
                        $classRoom->classRoomTeachers()->delete();
                        $classRoom->classRoomStudents()->delete();
                        $classRoom->activities()->delete();
                        $classRoom->calendars()->delete();
                        $classRoom->books()->delete();
                    }
                }
                $property?->grades()->delete();
            }

            $obj->properties()->delete();
            $obj->branchAdmins()->delete();
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
                ['Object Not Found']
            );
        }
    }
}
