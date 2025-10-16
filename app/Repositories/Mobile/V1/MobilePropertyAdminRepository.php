<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobilePropertyAdmin;
use App\Models\Properties\Property;
use App\Models\PropertyAdmin;
use App\Repositories\Common\V1\PropertyAdminRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobilePropertyAdminRepository extends PropertyAdminRepository implements IMobilePropertyAdmin
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'admin_id',
                'property_id'
            )->with('property:id,name')
                ->with('admin.user:id,first_name,last_name,image');

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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)
                ->with('property:id,name')
                ->with('admin.user:id,first_name,last_name,image')
                ->firstOrFail();

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['Could Not Find Data']
            );
        }
    }

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();

            $admin_ids = $object['admin_id'];
            $propertyId = $object['property_id'];

            $responses = [];

            foreach ($admin_ids as $admin_id) {
                $admins = [
                    'admin_id' => $admin_id,
                    'property_id' => $propertyId,
                ];

                $model = new $this->model;
                $model->fill($admins);
                $model->save();

                $responses[] = $admins; // Collect the created records for response
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $responses,
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

            $property = Property::findOrFail($id); // Replace Property with the actual model for your object

            $property->update(['property_id' => $object['property_id']]);
            if (isset($object['admin_id']) && is_array($object['admin_id'])) {

                $requestedAdminIds = $object['admin_id'];

                // Get existing admin IDs associated with the record
                $existingAdminIds = $property->propertyAdmins->pluck('admin_id')->toArray();

                // Delete all records associated with admin IDs from other properties
                PropertyAdmin::where('property_id', '!=', $id)
                    ->whereIn('admin_id', $existingAdminIds)
                    ->delete();

                // Loop through requested admin IDs
                foreach ($requestedAdminIds as $adminId) {
                    if (! in_array($adminId, $existingAdminIds)) {
                        // Create property admin association if not exists
                        PropertyAdmin::create([
                            'property_id' => $id,
                            'admin_id' => $adminId,
                        ]);
                    }
                }

                // Detach admin IDs that are not in the request or not from the same property
                PropertyAdmin::where('property_id', $id)
                    ->whereNotIn('admin_id', $requestedAdminIds)
                    ->delete();
            } else {
                // If no admin_ids provided, delete all admin associations for the record
                PropertyAdmin::where('property_id', $id)->delete();
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $property->propertyAdmins,
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

    public function mobileTransferAdmin($object): object
    {
        try {
            DB::beginTransaction();

            // Delete all records associated with admin ID from other properties
            PropertyAdmin::where('admin_id', $object['admin_id'])
                ->where('property_id', '!=', $object['property_id'])
                ->delete();

            $existingRecord = PropertyAdmin::where('admin_id', $object['admin_id'])
                ->where('property_id', $object['property_id'])
                ->first();

            if (! $existingRecord) {
                $obj = new $this->model;
                $obj->admin_id = $object['admin_id'];
                $obj->property_id = $object['property_id'];
                $obj->save();
            } else {
                $obj = $existingRecord;
            }

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
