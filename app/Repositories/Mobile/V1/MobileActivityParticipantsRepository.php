<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileActivityParticipants;
use App\Models\Infos\Activity;
use App\Models\Participant;
use App\Repositories\Common\V1\ActivityParticipantsRepository;
use Illuminate\Support\Facades\DB;

class MobileActivityParticipantsRepository extends ActivityParticipantsRepository implements IMobileActivityParticipants
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'activity_id',
                'student_id',
            )->orderBy($params->orderBy, $params->direction);

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
        } catch (\Exception $e) {
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
            $data = $this->builder->whereId($id)
                ->select(
                    'id',
                    'activity_id',
                    'student_id',
                )
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
            $activityId = $object->activity_id;
            $studentIds = $object->student_id;

            $responses = [];

            foreach ($studentIds as $studentId) {
                $participantData = [
                    'activity_id' => $activityId,
                    'student_id' => $studentId,
                ];

                $participant = new $this->model;
                $participant->fill($participantData);
                $participant->save();

                $responses[] = $participant; // Collect the created participants for response
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

            $activity = Activity::findOrFail($id); // Assuming Activity is the model for activities

            $activity->update(['activity_id' => $object['activity_id']]);

            if (isset($object['student_id']) && is_array($object['student_id'])) {
                $studentIds = $object['student_id'];

                // Get existing participant student IDs
                $existingParticipantStudentIds = $activity->participants->pluck('student_id')->toArray();

                foreach ($studentIds as $studentId) {
                    if (! in_array($studentId, $existingParticipantStudentIds)) {
                        // Create participant for student in the specific activity
                        Participant::create([
                            'activity_id' => $id,
                            'student_id' => $studentId,
                        ]);
                    }
                }

                // Detach participants that are not in the request
                Participant::where('activity_id', $id)
                    ->whereNotIn('student_id', $studentIds)
                    ->delete();
            } else {
                // If no student_id provided, delete all participants for the activity
                Participant::where('activity_id', $id)->delete();
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $activity,
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

            // Delete all related attendance records
            $obj->participants()->delete();

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
