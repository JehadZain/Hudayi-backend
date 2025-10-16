<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppParent;
use App\Models\Morphs\Address;
use App\Models\Morphs\Contact;
use App\Repositories\Common\V1\UserParentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppUserParentRepository extends UserParentRepository implements IAppParent
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->appParentWithUser();
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
                ->appParentWithUser()
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
            $user = $this->createObject($object['user'], $this->associate);
            //morph contact
            $contact = new Contact();
            $contact->label = $object['contact']['label'];
            $contact->type = $object['contact']['type'];
            $contact->value = $object['contact']['value'];

            $address = new Address();
            $address->label = $object['address']['label'];
            $address->country = $object['address']['country'];
            $address->city = $object['address']['city'];
            $address->state = $object['address']['state'];
            $address->line_1 = $object['address']['line_1'];
            $address->line_2 = $object['address']['line_2'];
            $address->floor = $object['address']['floor'];
            $address->flat = $object['address']['flat'];
            $address->lat = $object['address']['lat'];
            $address->long = $object['address']['long'];
            $address->location_url = $object['address']['location_url'];

            unset($object['user']);
            unset($object['contact']);
            unset($object['address']);

            $parent = $this->createObject($object, $this->model, false);
            $parent->user()->associate($user);
            $parent->save();
            $parent->contacts()->save($contact);
            $parent->addresses()->save($address);
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
            $obj->user_id = $object['user_id'];
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
