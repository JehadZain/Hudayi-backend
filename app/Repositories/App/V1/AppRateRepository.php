<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppRate;
use App\Repositories\Common\V1\RateRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppRateRepository extends RateRepository implements IAppRate
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'admin_id',
                'teacher_id',
                'student_count',
                'date',
                'start_date',
                'end_date',
                'correct_reading_skill',
                'teaching_skill',
                'academic_skill',
                'following_skill',
                'plan_commitment',
                'time_commitment',
                'student_commitment',
                'activity',
                'commitment_to_administrative_instructions',
                'exam_and_quizzes',
                'percentage',
                'score',
                'note',
            )->with('admin.user:id,first_name,last_name,image');

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
                'admin_id',
                'teacher_id',
                'student_count',
                'date',
                'start_date',
                'end_date',
                'correct_reading_skill',
                'teaching_skill',
                'academic_skill',
                'following_skill',
                'plan_commitment',
                'time_commitment',
                'student_commitment',
                'activity',
                'commitment_to_administrative_instructions',
                'exam_and_quizzes',
                'percentage',
                'score',
                'note',
            )->with('admin.user:id,first_name,last_name,image')
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
            $obj->admin_id = $object['admin_id'];
            $obj->teacher_id = $object['teacher_id'];
            $obj->student_count = $object['student_count'];
            $obj->date = $object['date'];
            $obj->start_date = $object['start_date'];
            $obj->end_date = $object['end_date'];
            $obj->correct_reading_skill = $object['correct_reading_skill'];
            $obj->teaching_skill = $object['teaching_skill'];
            $obj->academic_skill = $object['academic_skill'];
            $obj->following_skill = $object['following_skill'];
            $obj->plan_commitment = $object['plan_commitment'];
            $obj->time_commitment = $object['time_commitment'];
            $obj->student_commitment = $object['student_commitment'];
            $obj->activity = $object['activity'];
            $obj->commitment_to_administrative_instructions = $object['commitment_to_administrative_instructions'];
            $obj->exam_and_quizzes = $object['exam_and_quizzes'];
            $obj->note = $object['note'];

            // Specify fields to be considered for calculation
            $includedFields = array_filter([
                'correct_reading_skill' => $object['correct_reading_skill'],
                'teaching_skill' => $object['teaching_skill'],
                'academic_skill' => $object['academic_skill'],
                'following_skill' => $object['following_skill'],
                'plan_commitment' => $object['plan_commitment'],
                'time_commitment' => $object['time_commitment'],
                'student_commitment' => $object['student_commitment'],
                'activity' => $object['activity'],
                'commitment_to_administrative_instructions' => $object['commitment_to_administrative_instructions'],
                'exam_and_quizzes' => $object['exam_and_quizzes'],
            ], function ($value) {
                // Include both non-null and non-false values, including 0
                return $value !== null || $value !== false;
            });

            // Calculate total score
            $totalScore = array_sum($includedFields);

            // Calculate percentage
            $nonEmptyItemCount = count(array_filter($includedFields, function ($value) {
                return $value !== null && $value !== ''; // Include both non-null and non-empty values
            }));

//
            $total_value = $nonEmptyItemCount * 10;
            $percentage = ($totalScore / $total_value) * 100;
            $obj->percentage = $percentage;
            $obj->score = $totalScore;

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
            $obj->teacher_id = $object['teacher_id'];
            $obj->student_count = $object['student_count'];
            $obj->date = $object['date'];
            $obj->start_date = $object['start_date'];
            $obj->end_date = $object['end_date'];
            $obj->correct_reading_skill = $object['correct_reading_skill'];
            $obj->teaching_skill = $object['teaching_skill'];
            $obj->academic_skill = $object['academic_skill'];
            $obj->following_skill = $object['following_skill'];
            $obj->plan_commitment = $object['plan_commitment'];
            $obj->time_commitment = $object['time_commitment'];
            $obj->student_commitment = $object['student_commitment'];
            $obj->activity = $object['activity'];
            $obj->commitment_to_administrative_instructions = $object['commitment_to_administrative_instructions'];
            $obj->exam_and_quizzes = $object['exam_and_quizzes'];
            $obj->note = $object['note'];


            // Specify fields to be considered for calculation
            $includedFields = array_filter([
                'correct_reading_skill' => $object['correct_reading_skill'],
                'teaching_skill' => $object['teaching_skill'],
                'academic_skill' => $object['academic_skill'],
                'following_skill' => $object['following_skill'],
                'plan_commitment' => $object['plan_commitment'],
                'time_commitment' => $object['time_commitment'],
                'student_commitment' => $object['student_commitment'],
                'activity' => $object['activity'],
                'commitment_to_administrative_instructions' => $object['commitment_to_administrative_instructions'],
                'exam_and_quizzes' => $object['exam_and_quizzes'],
            ], function ($value) {
                // Include both non-null and non-false values, including 0
                return $value !== null || $value !== false;
            });

            // Calculate total score
            $totalScore = array_sum($includedFields);

            // Calculate percentage
            $nonEmptyItemCount = count(array_filter($includedFields, function ($value) {
                return $value !== null && $value !== ''; // Include both non-null and non-empty values
            }));

//
            $total_value = $nonEmptyItemCount * 10;
            $percentage = ($totalScore / $total_value) * 100;
            $obj->percentage = $percentage;
            $obj->score = $totalScore;
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
