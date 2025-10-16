<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileBook;
use App\Repositories\Common\V1\BookRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileBookRepository extends BookRepository implements IMobileBook
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'size',
                'property_type',
                'book_type',
                'paper_count',
                'author_name',
                'image'
            );

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
                [$e->getMessage()]
            );
        }
    }

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'name',
                'size',
                'property_type',
                'book_type',
                'paper_count',
                'author_name',
                'image')->firstOrFail();

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
            $obj->name = $object['name'];
            $obj->size = $object['size'];
            $obj->paper_count = $object['paper_count'];
            $obj->author_name = $object['author_name'];
            $obj->property_type = $object['property_type'];
            $obj->book_type = $object['book_type'];

            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/books-interview';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $obj->image = $path.'/'.$filename;
            }

            if ($this->builder->where('name', $obj->name)->exists()) {
                DB::rollBack();

                return $this->setResponse(
                    parent::FAILED,
                    [],
                    ['Object name is already exist']
                );
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

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj->name = $object['name'];
            $obj->size = $object['size'];
            $obj->paper_count = $object['paper_count'];
            $obj->author_name = $object['author_name'];
            $obj->property_type = $object['property_type'];
            $obj->book_type = $object['book_type'];

            // Check if the image is present in the request
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/books-interview';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

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

    public function mobileGetAllSchoolBooks($params = null): object
    {

        try {
            $books = $this->builder->where('property_type', 'school')->select('id', 'name', 'author_name', 'book_type')->get();

            $filter = $this->filter($params);

            if ($this->builder->count() > 0) {
                $data = $filter->paginate();

                return $this->setResponse(parent::SUCCESS, $books);
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

    public function mobileGetAllMosqueBooks($params = null): object
    {
        try {
            $books = $this->builder->where('property_type', 'mosque')->select('id', 'name', 'author_name', 'book_type')->get();

            if ($this->builder->count() > 0) {
                return $this->setResponse(parent::SUCCESS, $books);
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
}
