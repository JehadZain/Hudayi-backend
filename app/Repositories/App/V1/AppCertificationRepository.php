<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppCertification;
use App\Repositories\Common\V1\CertificationRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppCertificationRepository extends CertificationRepository implements IAppCertification
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'user_id',
                'image',
                'issuing_date',
                'expiration_date',
            )->with('user:id,first_name,last_name,image,status');

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
                'user_id',
                'image',
                'issuing_date',
                'expiration_date',
            )->with('user:id,first_name,last_name,image,status')
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
            $obj->user_id = $object['user_id'];
            $obj->issuing_date = $object['issuing_date'];
            $obj->expiration_date = $object['expiration_date'];

            // Check if a new image is included in the update data
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                // Download the image file
                $imageData = file_get_contents($imageUrl);

                $path = 'storage/images/certifications';
                // Generate a unique filename for the image
                $extension = $imageUrl->getClientOriginalExtension();
                $filename = uniqid().'.'.$extension;
                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the downloaded image data to disk
                file_put_contents($fullPath, $imageData);

                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

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
            $obj->user_id = $object['user_id'];
            $obj->issuing_date = $object['issuing_date'];
            $obj->expiration_date = $object['expiration_date'];

            // Check if a new image is included in the update data
            if (! empty($object['image'])) {
                $imageUrl = $object['image'];
                // Download the image file
                $imageData = file_get_contents($imageUrl);

                $path = 'storage/images/certifications';
                // Generate a unique filename for the image
                $extension = $imageUrl->getClientOriginalExtension();
                $filename = uniqid().'.'.$extension;
                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the downloaded image data to disk
                file_put_contents($fullPath, $imageData);

                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

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
