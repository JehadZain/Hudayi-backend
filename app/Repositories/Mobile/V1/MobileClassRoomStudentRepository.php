<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileClassRoomStudent;
use App\Models\Properties\ClassRoom;
use App\Models\Properties\ClassRoomStudent;
use App\Repositories\Common\V1\ClassRoomStudentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileClassRoomStudentRepository extends ClassRoomStudentRepository implements IMobileClassRoomStudent
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'class_room_id',
                'student_id',
                'joined_at',
                'left_at',
            );

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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->where('class_room_id', $id)->select(
                'id',
                'class_room_id',
                'student_id',
                'joined_at',
                'left_at',
            )
                ->get();

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
            $responses = [];

            foreach ($object['student_id'] as $studentId) {
                // Check if student ID already exists
                $existingObject = ClassRoomStudent::where('student_id', $studentId)
                    ->whereNull('left_at') // Check if left_at is not set->first();
                    ->first();
                if ($existingObject) {
                    $existingClassRoomId = $existingObject->class_room_id;
                    DB::rollBack(); // Rollback the transaction

                    return $this->setResponse(
                        parent::FAILED,
                        null,
                        ["Student ID $studentId is already in use in class room $existingClassRoomId."]
                    );
                }

                // Create the object
                $obj = new $this->model;
                $obj->class_room_id = $object['class_room_id'];
                $obj->student_id = $studentId;
                $obj->joined_at = $object['joined_at'];
                $obj->left_at = $object['left_at'];
                $obj->save();

                $responses[] = $obj; // Collect the created objects for response
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

            $classRoom = ClassRoom::findOrFail($id); // Assuming ClassRoom is the model for classrooms
            $newStudentIds = $object['student_id'] ?? [];

            // Get existing student IDs
            $existingStudentIds = $classRoom->classRoomStudents->whereNull('left_at')->pluck('student_id')->toArray();

            // Check if the existing student IDs are the same as the new student IDs
            if ($existingStudentIds === $newStudentIds) {
                DB::rollBack();

                return $this->setResponse(
                    parent::SUCCESS,
                    $classRoom,
                    ['No changes were made.']
                );
            } else {

                // Check for student IDs that already exist in other classrooms
                $conflictingStudentIds = ClassRoomStudent::whereIn('student_id', $newStudentIds)
                    ->whereNotIn('class_room_id', [$id])
                    ->whereNull('left_at')
                    ->pluck('student_id')
                    ->toArray();

                if (! empty($conflictingStudentIds)) {
                    // Return an error response indicating conflicting student IDs
                    DB::rollBack();

                    return $this->setResponse(
                        parent::FAILED,
                        null,
                        ['Some student IDs are already assigned to other classrooms: '.implode(', ', $conflictingStudentIds)]
                    );
                }

                foreach ($newStudentIds as $studentId) {
                    if (! in_array($studentId, $existingStudentIds)) {
                        // Create participant for student in the specific classroom
                        ClassRoomStudent::create([
                            'class_room_id' => $id,
                            'student_id' => $studentId,
                            'joined_at' => $object['joined_at'] ?? now(),
                            'left_at' => $object['left_at'] ?? null,
                        ]);
                    }
                }

                // Detach participants that are not in the request
                ClassRoomStudent::where('class_room_id', $id)
                    ->whereNotIn('student_id', $newStudentIds)
                    ->update(['left_at' => now()]);

                // If no student_id provided, delete all students for the classroom
                if (empty($newStudentIds)) {
                    ClassRoomStudent::where('class_room_id', $id)->delete();
                }

                DB::commit();

                return $this->setResponse(
                    parent::SUCCESS,
                    $classRoom,
                );

            }

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
            $obj->left_at = now();
            $obj->save();

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
