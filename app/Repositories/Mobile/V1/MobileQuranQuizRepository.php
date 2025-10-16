<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileQuranQuiz;
use App\Repositories\Common\V1\QuranQuizRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileQuranQuizRepository extends QuranQuizRepository implements IMobileQuranQuiz
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'juz',
                'page',
                'date',
                'exam_type',
                'score',
                'student_id',
                'teacher_id',
            )->with(['teacher.user' => function ($query) {
                $query->withTrashed()->select('id', 'first_name', 'last_name', 'image');
            }])
                ->with(['student.user' => function ($query) {
                    $query->withTrashed()->select('id', 'first_name', 'last_name', 'image');
                }])
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
                'juz',
                'page',
                'date',
                'exam_type',
                'score',
                'student_id',
                'teacher_id',
            )->with(['teacher.user' => function ($query) {
                $query->withTrashed()->select('id', 'first_name', 'last_name', 'image');
            }])
                ->with(['student.user' => function ($query) {
                    $query->withTrashed()->select('id', 'first_name', 'last_name', 'image');
                }])
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $obj = new $this->model;
            $obj->name = $object['name'];
            $obj->juz = $object['juz'];
            $obj->page = $object['page'];
            $obj->date = $object['date'];
            $obj->exam_type = $object['exam_type'];
            $obj->score = $object['score'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
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
            $obj->juz = $object['juz'];
            $obj->page = $object['page'];
            $obj->date = $object['date'];
            $obj->score = $object['score'];
            $obj->exam_type = $object['exam_type'];
            $obj->student_id = $object['student_id'];
            $obj->teacher_id = $object['teacher_id'];
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
}
