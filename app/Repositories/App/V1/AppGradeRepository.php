<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppGrade;
use App\Models\Infos\Grade;
use App\Repositories\Common\V1\GradeRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AppGradeRepository extends GradeRepository implements IAppGrade
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'description',
                'property_id'
            )->with([
                'classRooms' => function ($query) {
                    $query->select('id', 'name', 'capacity', 'grade_id', 'image', 'is_approved')
                        ->orderBy('is_approved', 'asc'); // Order classRooms by is_approved
                },
                'property:id,name,property_type',
            ])->orderBy($params->orderBy, $params->direction);

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

    public function appById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'name',
                'description',
                'property_id'
            )->with('classRooms:id,name,capacity,grade_id,image,is_approved')
                ->with('property:id,name,property_type')
                // ->with('classRooms.classRoomTeachers.teacher.user:id,first_name,last_name,gender')
                // ->with('classRooms.classRoomStudents.student.user:id,first_name,last_name,gender')
                // ->with('classRooms.sessions:id,date,start_at,duration,class_room_id')
                //->with('subjects:id,name,description,grade_id')
                ->firstOrFail();

            // Call the classStatistics function to get the statistics
            $statistics = $this->appGetGradeStatistics($id);

            // Convert the data to an array if it's not already
            $dataArray = $data->toArray();

            // Merge the statistics into the data array
            if (is_object($statistics) && property_exists($statistics, 'data')) {
                $dataArray['statistics'] = $statistics->data;
            } elseif (is_array($statistics) && isset($statistics['data'])) {
                $dataArray['statistics'] = $statistics['data'];
            } else {
                $dataArray['statistics'] = $statistics;
            }



            return $this->setResponse(
                parent::SUCCESS,
                $dataArray,
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    private function getStudentExamPages($exams)
    {
        $pages = [];
        foreach ($exams as $exam) {
            $exam_pages = explode(',', $exam->page); // Split pages into an array
            $pages = array_merge($pages, $exam_pages); // Merge with the main array
        }
        $pages = array_unique($pages); // Remove duplicate pages
        return count($pages); // Count the unique pages
    }

    public function appGetGradeStatistics(string $id): object
    {
        try {
            // Get the property with its grades, classrooms, sessions, and students
            $grade = $this->builder->whereId($id)->with(['classRooms' => function ($query) {
                $query->with(['sessions', 'classRoomStudents.student', 'classRoomTeachers.teacher', 'activities']);
            }])->findOrFail($id);

            // Get the start and end dates for the previous month
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();

            //          Get the counts of each model in the property
            $classroomsCount = $grade->classRooms
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $approvedStudentsCount = $grade->classRooms->sum(function ($classroom) use ($startDate, $endDate) {
                return $classroom->classRoomStudents()
                    ->whereHas('student.user', function ($query) {
                        $query->where('status', 1)->where('is_approved', 1);;
                    })
                    // ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            });

            $pendingStudentsCount = $grade->classRooms->sum(function ($classroom) use ($startDate, $endDate) {
                return $classroom->classRoomStudents()
                    ->whereHas('student.user', function ($query) {
                        $query->where('status', 0);
                    })
                    // ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            });

            $teachersCount = $grade->classRooms->sum(function ($classroom) use ($startDate, $endDate) {
                return $classroom->classRoomTeachers
//                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            });

            $sessionsCount = $grade->classRooms->sum(function ($classroom) use ($startDate, $endDate) {
                return $classroom->sessions
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            });

            $activitiesCount = $grade->classRooms->sum(function ($classroom) use ($startDate, $endDate) {
                return $classroom->activities
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            });

            $studentStatistics = [
                'face_to_face_quiz_pages' => 0,
                'absence_quiz_pages' => 0,
                'write_arabic_reading_quiz_pages' => 0,
                'quizzes_count' => 0,
                'interviews_count' => 0,
                'personal_interviews_count' => 0,
            ];

            // Iterate through each class within the grade
            foreach ($grade->classrooms as $classroom) {
                $students = $classroom->classRoomStudents->map(function ($classRoomStudent) {
                    return $classRoomStudent->student;
                });

                // Calculate student statistics for each class
                foreach ($students as $student) {
                    // Calculate face to face quiz pages
                    $faceToFaceQuiz = $student->quranQuizzes()
                        ->where('exam_type', 'recitationFromQuran')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get(); // Execute the query and retrieve the results as a collection

                    $studentStatistics['face_to_face_quiz_pages'] += $this->getStudentExamPages($faceToFaceQuiz);

                    // Calculate absence quiz pages
                    $absenceQuiz = $student->quranQuizzes()
//                        ->where('score', '>', 25)
                        ->where('exam_type', 'memorization')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get(); // Execute the query and retrieve the results as a collection
                    $studentStatistics['absence_quiz_pages'] += $this->getStudentExamPages($absenceQuiz);

                    // Calculate write arabic reading quiz pages
                    $correctArabicReadingQuiz = $student->quranQuizzes()
//                        ->where('score', '>', 25)
                        ->where('exam_type', 'correctArabicReading')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get(); // Execute the query and retrieve the results as a collection
                    $studentStatistics['write_arabic_reading_quiz_pages'] += $this->getStudentExamPages($correctArabicReadingQuiz);

                    // Count quizzes within the specified date range
                    $studentStatistics['quizzes_count'] += $student->quizzes()
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();

                    // Count interviews with type 'book' within the specified date range
                    $studentStatistics['interviews_count'] += $student->interviews()
                        ->where('type', 'book')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();

                    // Count interviews with type not 'book' within the specified date range
                    $studentStatistics['personal_interviews_count'] += $student->interviews()
                        ->where('type', '<>', 'book')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();
                }
            }

            //          Prepare the data to be returned
            $propertyType = $grade->property->property_type;
            if ($propertyType === 'school') {
                $data = [
                    [
                        'name' => 'Teachers',
                        'count' => $teachersCount,
                    ],
                    [
                        'name' => 'ClassRooms',
                        'count' => $classroomsCount,
                    ],

                    [
                        'name' => 'Approved Students',
                        'count' => $approvedStudentsCount,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $activitiesCount,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $studentStatistics['interviews_count'],
                    ], [
                        'name' => 'Personal Interviews',
                        'count' => $studentStatistics['personal_interviews_count'],
                    ],
                    [
                        'name' => 'Face-to-Face-Quiz-school',
                        'count' => $studentStatistics['face_to_face_quiz_pages'],
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => $studentStatistics['absence_quiz_pages'],
                    ],
                    //                    [
                    //                        'name' => 'Correct Arabic Reading Quiz',
                    //                        'count' => $studentStatistics['write_arabic_reading_quiz_pages']
                    //                    ],
                    //

                ];
            } elseif ($propertyType === 'mosque') {
                $data = [

                    [
                        'name' => 'ClassRooms',
                        'count' => $classroomsCount,
                    ],
                    [
                        'name' => 'Teachers',
                        'count' => $teachersCount,
                    ],
                    [
                        'name' => 'Approved Students',
                        'count' => $approvedStudentsCount,
                    ],
                    [
                        'name' => 'Pending Students',
                        'count' => $pendingStudentsCount,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $activitiesCount,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $studentStatistics['interviews_count'],
                    ],
                    [
                        'name' => 'Face-to-Face Quiz',
                        'count' => $studentStatistics['face_to_face_quiz_pages'],
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => $studentStatistics['absence_quiz_pages'],
                    ],
                    [
                        'name' => 'Correct Arabic Reading Quiz',
                        'count' => $studentStatistics['write_arabic_reading_quiz_pages'],
                    ],
                ];
            }
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
            $obj->description = $object['description'];
            $obj->property_id = $object['property_id'];
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
            $obj->name = $object['name'];
            $obj->description = $object['description'];
            $obj->property_id = $object['property_id'];

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

            $obj->classRooms()->delete();

            $classRooms = $obj->classRooms;
            foreach ($classRooms as $classRoom) {
                $classRoom->sessions()->delete();
                $classRoom->classRoomTeachers()->delete();
                $classRoom->classRoomStudents()->delete();
                $classRoom->activities()->delete();
                $classRoom->calendars()->delete();
                $classRoom->books()->delete();
            }
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
