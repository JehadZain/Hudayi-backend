<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppClassRoom;
use App\Models\Infos\Book;
use App\Models\Properties\ClassRoom;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Repositories\Common\V1\ClassRoomRepository;
use Illuminate\Support\Facades\DB;

class AppClassRoomRepository extends ClassRoomRepository implements IAppClassRoom
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
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

    public function appAllClassesByTeacherId(string $Id, $params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->with('grade')
                ->whereHas('classRoomTeachers', function ($query) use ($Id) {
                    // Filter classes that have a teacher with the specified ID
                    $query->where('teacher_id', $Id);
                })
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->with('books');
            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
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

    public function appAllSchoolClassroom($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->whereHas('grade.property', function ($query) {
                $query->where('property_type', 'school');
            })->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function appAllMosqueClassroom($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->whereHas('grade.property', function ($query) {
                $query->where('property_type', 'mosque');
            })->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllApprovedClasses($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->where('is_approved', true)
                ->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllApprovedSchoolClasses($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->where('is_approved', true)
                ->whereHas('grade.property', function ($query) {
                    $query->where('property_type', 'school');
                })->with('grade')
                ->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllApprovedMosqueClasses($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->where('is_approved', true)
                ->whereHas('grade.property', function ($query) {
                    $query->where('property_type', 'mosque');
                })->with('grade')
                ->with('classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status')
                ->with('classRoomStudents.student.user:id,first_name,last_name,gender,image,status')
                ->with('sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type')
                ->with('calendars')
                ->with('activities.activityType')
                ->with('activities.teacher.user:id,first_name,last_name')
                ->with('activities.participants.student.user:id,first_name,last_name')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllClassesNotApproved($params = null): object
    {

        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'description',
                'grade_id',
                'image'
            )->with('grade.property:id,name,property_type')
                ->where('is_approved', false)
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllPendingSchoolClasses($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'description',
                'grade_id',
                'image'
            )->whereHas('grade.property', function ($query) {
                $query->where('property_type', 'school');
            })->with('grade.property:id,name,property_type')->where('is_approved', false)
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function getAllPendingMosqueClasses($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'description',
                'grade_id',
                'image'
            )->whereHas('grade.property', function ($query) {
                $query->where('property_type', 'mosque');
            })->with('grade.property:id,name,property_type')->where('is_approved', false)
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(
                    parent::NO_DATA,
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

    public function appById(string $id): object
    {

        //        $classRoom = ClassRoom::findOrFail($id);
        //        $grade = $classRoom->grade; // Get the `Grade` model for the given `ClassRoom` instance
        //        $properties = $grade->property->property_type; // Get the `Property` models for the given `Grade` instance
        //        dd($properties); // Debug the `$properties` result

        try {
            $data = $this->builder->whereId($id)->select(
                'id',
                'name',
                'capacity',
                'image',
                'is_approved',
                'description',
                'grade_id'
            )->with([
                'classRoomTeachers.teacher.user:id,first_name,last_name,gender,image,status',
                'classRoomStudents.student.user:id,first_name,last_name,gender,image,status',
                'sessions:id,name,description,date,start_at,duration,class_room_id,subject_name,place,type',
                'sessions' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'calendars',
                'activities.activityType',
                'activities.teacher.user:id,first_name,last_name',
                'activities.participants.student.user:id,first_name,last_name',
                'activities' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'books',
            ])
                ->firstOrFail();

            // Call the classStatistics function to get the statistics
            $statistics = $this->classStatistics($id);

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

    /**
     * Creates a new object in the database with the given data.
     *
     * @param array $object The object data to create.
     * @return object A response object with the newly created object or an error message.
     */
    public function appCreateObject($object): object
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            $existingClass = $this->builder->where('name', $object['name'])->first();

            if ($existingClass && !$existingClass->trashed()) {
                // A class with the same name already exists, return an error message
                DB::rollBack();
                return $this->setResponse(
                    parent::FAILED,
                    null,
                    ['Class with the same name already exists.'],
                );
            }


            // Create a new object with the given data
            $obj = new $this->model;
            $obj->name = $object['name'];
            $obj->capacity = $object['capacity'];
            $obj->grade_id = $object['grade_id'];
            $obj->is_approved = $object['is_approved'];
            $obj->description = $object['description'];

            // If an image is included in the create data,
            // save it to the server and set the object's image attribute
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/class-rooms';

                // Generate a unique filename for the image
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $obj->image = $path . '/' . $filename;
            }

            // Save the new object to the database
            $obj->save();

            // Commit the transaction
            DB::commit();

            // Return a response with the newly created object
            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['ClassRoom Created Successfully'],
            );
        } catch (\Exception $e) {
            // Roll back the transaction
            DB::rollBack();

            // Return a response with an error message
            return $this->setResponse(
                parent::FAILED,
                null,
                ["Couldn't Create ClassRoom"],
            );
        }
    }

    /**
     * Updates an object in the database with the given data.
     *
     * @param array $object The object data to update.
     * @param int $id The ID of the object to update.
     * @return object A response object with the updated object or an error message.
     */
    public function appUpdateObject($object, $id): object
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Retrieve the object to update from the database
            $obj = $this->builder->whereId($id)->firstOrFail();

            // Check if the new name is different from the existing name
            if ($object['name'] !== $obj->name) {
                // New name is different, check if it's a duplicate
                $existingClass = ClassRoom::where('name', $object['name'])->first();

                if ($existingClass && !$existingClass->trashed()) {
                    // A class with the new name already exists, return an error message
                    DB::rollBack();
                    return $this->setResponse(
                        parent::FAILED,
                        null,
                        ['Class with the same name already exists.'],
                    );
                }
            }

            // Update the object's attributes with the new data
            $obj->name = $object['name'];
            $obj->capacity = $object['capacity'];
            $obj->grade_id = $object['grade_id'];
            $obj->is_approved = $object['is_approved'];
            $obj->description = $object['description'];

            // Check if a new image is included in the update data
            if (isset($object['image'])) {
                $imageUrl = $object['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/class-rooms';

                // Generate a unique filename for the image
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Check if there's an old image
                if (!empty($obj->image)) {
                    // Delete the old image file
                    $oldImagePath = public_path($obj->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store the path link in the database
                $obj->image = $path . '/' . $filename;
            }

            // Save the updated object to the database
            $obj->save();

            // Commit the transaction
            DB::commit();

            // Return a response with the updated object
            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['ClassRoom Updated Successfully'],
            );
        } catch (\Exception $e) {
            // Roll back the transaction
            DB::rollBack();

            // Return a response with an error message
            return $this->setResponse(
                parent::FAILED,
                null,
                [$e->getMessage()]
            //                ["Couldn't Update ClassRoom"],
            );
        }
    }

    public function appDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            //            dd($obj->sessions,$obj->classRoomTeachers,$obj->classRoomTeachers,$obj->activities,$obj->calendars);
            $obj->sessions()->delete();
            $obj->classRoomTeachers()->delete();
            $obj->classRoomStudents()->delete();
            $obj->activities()->delete();
            $obj->calendars()->delete();
            $obj->books()->delete();

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

    public function appGetAllStudentsWithoutClassroom()
    {
        try {
            $students = Student::leftJoin('class_room_students', 'students.id', '=', 'class_room_students.student_id')
                ->leftJoin('users', 'users.id', '=', 'students.user_id')
                ->whereNull('class_room_students.class_room_id')
                ->orWhereNull('class_room_students.deleted_at')
                ->select('students.id', 'students.user_id', 'users.first_name', 'users.last_name')
                ->get();

            return $this->setResponse(
                parent::SUCCESS,
                $students,
            );
        } catch (\Exception $e) {

            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function appGetAllTeachersWithoutClassroom()
    {
        try {
            $students = Teacher::leftJoin('class_room_teachers', 'teachers.id', '=', 'class_room_teachers.teacher_id')
                ->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                ->whereNull('class_room_teachers.class_room_id')
                ->orWhereNull('class_room_teachers.deleted_at')
                ->select('teachers.id', 'teachers.user_id', 'users.first_name', 'users.last_name')
                ->get();

            return $this->setResponse(
                parent::SUCCESS,
                $students,
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

    public function classStatistics(string $id): object
    {
        try {
            // Get the start and end dates for the previous month
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
            $classroom = ClassRoom::with(['activities', 'sessions'])->findOrFail($id);


            $approvedStudents = $classroom->classRoomStudents()
                ->whereNull('left_at')
                ->whereHas('student.user', function ($query) {
                    return $query->where('status', 1)->where('is_approved', 1);
                })
                ->count();


            $unapprovedStudents = $classroom->classRoomStudents()
                ->whereNull('left_at')
                ->whereHas('student.user', function ($query) {
                    return $query->where('status', 0);
                })
                ->count();

//            $classroom = ClassRoom::findOrFail($id);
            $students = $classroom->classRoomStudents
                ->whereNull('left_at')
                ->map(function ($classRoomStudent) {
                return $classRoomStudent->student;
            });
            $studentStatistics = [
                'face_to_face_quiz_pages' => 0,
                'absence_quiz_pages' => 0,
                'write_arabic_reading_quiz_pages' => 0,
                'quizzes_count' => 0,
                'interviews_count' => 0,
                'personal_interviews_count' => 0,
            ];

            // Calculate student statistics
            foreach ($students as $student) {
                // Check if $student is not null before proceeding
                if ($student) {
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

            $activities = $classroom->activities
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $sessions = $classroom->sessions
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();


            $propertyType = $classroom->grade->property->property_type;
            if ($propertyType === 'school') {
                $data = [

                    [
                        'name' => 'Approved Students',
                        'count' => $approvedStudents,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $activities,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $studentStatistics['interviews_count'],
                    ],
                    [
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
                ];
            } elseif ($propertyType === 'mosque') {
                $data = [

                    [
                        'name' => 'Approved Students',
                        'count' => $approvedStudents,
                    ],
                    [
                        'name' => 'Pending Students',
                        'count' => $unapprovedStudents,
                    ],
                    [
                        'name' => 'Sessions',
                        'count' => $sessions,
                    ],
                    [
                        'name' => 'Quizzes',
                        'count' => $studentStatistics['quizzes_count'],
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $activities,
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
                    [
                        'name' => 'Interviews',
                        'count' => $studentStatistics['interviews_count'],
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

    public function getClassRoomBooks($id): object
    {
        try {
            $classRoom = ClassRoom::findOrFail($id);
            $books = $classRoom->books;

            return $this->setResponse(
                parent::SUCCESS,
                $books
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::FAILED,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function appCreatClassRoomBook($bookId, $classRoomId): object
    {
        try {
            DB::beginTransaction();
            $classRoom = ClassRoom::findOrFail($classRoomId);
            //            dd($classRoom->books()->onlyTrashed()->get());
            // Check if the book is already associated with the class room
            if ($classRoom->books()->where('book_id', $bookId)->exists()) {
                DB::rollBack();

                return $this->setResponse(
                    parent::FAILED,
                    [],
                    ['Book is already added to class room']
                );
            }

            // Manually set the created_at timestamp
            $createdAt = now(); // or use any other desired timestamp value

            // Attach the book to the class room with the created_at timestamp
            $classRoom->books()->attach($bookId, ['created_at' => $createdAt]);

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                ['book_id' => $bookId, 'class_room_id' => $classRoomId],
                ['Book has been added to class room successfully']
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

    public function appDeleteClassRoomBook($bookId, $classRoomId): object
    {
        try {
            DB::beginTransaction();
            $classRoom = ClassRoom::findOrFail($classRoomId);
            $classRoom->books()->detach($bookId);
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                ['book_id' => $bookId, 'class_room_id' => $classRoomId],
                ['Book has been removed from class room successfully']
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
