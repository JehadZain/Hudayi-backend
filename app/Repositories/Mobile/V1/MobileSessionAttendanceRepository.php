<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileSessionAttendance;
use App\Models\Infos\Session;
use App\Models\Morphs\Attendance;
use App\Models\SessionAttendance;
use App\Repositories\Common\V1\SessionAttendanceRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MobileSessionAttendanceRepository extends SessionAttendanceRepository implements IMobileSessionAttendance
{
    public function mobileAll($params = null): object
    {
        try {
            $data = $this->builder->select(
                'id',
                'session_id',
                'student_id',
            )->with('student.user:id,first_name,last_name,gender,image')
                ->orderBy($params->orderBy, $params->direction)
                ->get();

            if ($this->builder->count() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
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

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'session_id',
                'student_id',
            )->with('student.user:id,first_name,last_name,gender')
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

            $session_id = $object['session_id'];
            $student_ids = $object['student_id'];

            $responses = [];

            foreach ($student_ids as $student_id) {
                $attendanceData = [
                    'session_id' => $session_id,
                    'student_id' => $student_id,
                ];

                $attendance = new $this->model;
                $attendance->fill($attendanceData);
                $attendance->save();

                $responses[] = $attendance; // Collect the created attendance records for response
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

            $session = Session::findOrFail($id); // Assuming Session is the model for sessions

            $session->update(['session_id' => $object['session_id']]);

            if (isset($object['student_id']) && is_array($object['student_id'])) {
                $studentIds = $object['student_id'];

                // Get existing attendance student IDs
                $existingAttendanceStudentIds = $session->sessionAttendances->pluck('student_id')->toArray();

                foreach ($studentIds as $studentId) {
                    if (! in_array($studentId, $existingAttendanceStudentIds)) {
                        // Create attendance for student in the specific session
                        SessionAttendance::create([
                            'session_id' => $id,
                            'student_id' => $studentId,
                        ]);
                    }
                }

                // Detach attendance that are not in the request
                SessionAttendance::where('session_id', $id)
                    ->whereNotIn('student_id', $studentIds)
                    ->delete();
            } else {
                // If no student_id provided, delete all attendance for the session
                SessionAttendance::where('session_id', $id)->delete();
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $session,
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
