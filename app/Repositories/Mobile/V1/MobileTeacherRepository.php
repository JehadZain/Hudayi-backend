<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileTeacher;
use App\Models\Infos\Status;
use App\Models\Morphs\Address;
use App\Models\Properties\ClassRoomTeacher;
use App\Models\Properties\Property;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Models\Users\User;
use App\Repositories\Common\V1\TeacherRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileTeacherRepository extends TeacherRepository implements IMobileTeacher
{
    public function mobileAll($params = null): object
    {
        try {
            $this->builder = $this->builder->appTeacherWithUser()
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
            $data = $this->builder->whereId($id)
                ->appTeacherWithUser()
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

    public function mobileGetAllOrgTeacher($params = null): object
    {
        try {
            // Fetch the students with user information
            $teachersQuery = $this->builder->appTeacherWithUser();

            $searchWithUser = request()->input('searchWithUser');

            if (!empty($searchWithUser)) {
                $teachersQuery = $teachersQuery->where(function ($query) use ($searchWithUser) {
                    $query->orWhereHas('user', function ($subquery) use ($searchWithUser) {
                        $subquery->where('first_name', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('last_name', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('email', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('username', 'LIKE', '%' . $searchWithUser . '%');
                    });
                });
            }

            // Paginate the transformed data
            $paginatedData = $teachersQuery->paginate();

            if ($paginatedData->isEmpty()) {
                return $this->setResponse(
                    parent::NO_DATA,
                    null,
                    ['NO_DATA']
                );
            }

            // Select specific attributes for the response
            $selectedAttributesData = $paginatedData->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'user_id' => $teacher->user_id,
                    'name' => $teacher->user?->first_name . ' ' . $teacher->user?->last_name,
                    'status' => $teacher->user?->status,
                    'identity_number' => $teacher->user?->identity_number,
                    'image' => $teacher?->user?->image,
                    'gender' => $teacher?->user?->gender,
                    'is_approved' => $teacher->user?->is_approved,

                ];
            });
            $selectedAttributesData = $selectedAttributesData->sortBy('is_approved')->values()->all();

            $data = [
                'current_page' => $paginatedData->currentPage(),
                'data' => $selectedAttributesData,
                'first_page_url' => $paginatedData->url(1),
                'from' => $paginatedData->firstItem(),
                'last_page' => $paginatedData->lastPage(),
                'last_page_url' => $paginatedData->url($paginatedData->lastPage()),
                'links' => $paginatedData->links(),
                'next_page_url' => $paginatedData->nextPageUrl(),
                'path' => $paginatedData->url($paginatedData->currentPage()),
                'per_page' => $paginatedData->perPage(),
                'prev_page_url' => $paginatedData->previousPageUrl(),
                'to' => $paginatedData->lastItem(),
                'total' => $paginatedData->total(),
            ];

            return $this->setResponse(
                parent::SUCCESS,
                $data // Use the modified data with selected attributes
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                [$e->getMessage()]
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

    public function teacherStatistics(string $id): object
    {
        try {

            $teacher = $this->builder->withCount(['classRooms', 'sessions', 'quranQuizzes', 'quizzes', 'interviews', 'notes', 'activity'])->findOrFail($id);

            // Retrieve interviews where type is 'book' within the previous month
            $book_interview_count = $teacher->interviews()
                ->where('type', 'book')
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Retrieve interviews where type is 'book' within the previous month
            $personal_interview_count = $teacher->interviews()
                ->where('type', 'pedagogical')
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $face_to_face_quiz = $teacher->quranQuizzes()
//                ->where('score', '>', 25)
                ->where('exam_type', 'recitationFromQuran')
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $quran_quiz_pages = $this->getStudentExamPages($face_to_face_quiz);

            $absence_quiz = $teacher->quranQuizzes()
//                ->where('score', '>', 25)
                ->where('exam_type', 'memorization')
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $absence_quiz_pages = $this->getStudentExamPages($absence_quiz);

            $write_arabic_reading_quiz = $teacher->quranQuizzes()
//                ->where('score', '>', 25)
                ->where('exam_type', 'correctArabicReading')
//                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $write_arabic_reading_quiz_pages = $this->getStudentExamPages($write_arabic_reading_quiz);


            // Get the first class ID associated with the teacher (not deleted and left_at is null)
            $classId =  $teacher->classRooms()
                   ->wherePivot('teacher_id', $id)
                   ->wherePivot('left_at', null)
                   ->whereNull('class_room_teachers.deleted_at')
                   ->pluck('class_room_id')
                   ->first();

            // Get the count of students in the class
            // Get the count of students in the class where left_at is null
            $studentsCount = Student::whereHas('classRooms', function ($query) use ($classId) {
                $query->where('class_room_students.class_room_id', $classId)
                    ->where('class_room_students.left_at', null);
            })->count();


            $property = $teacher->user->property_id;
            $propertyType = Property::whereId($property)->first()->property_type;
            if ($propertyType === 'school') {

                $data = [
                    [
                        'name' => 'Students',
                        'count' => $studentsCount,
                    ],
                    [
                        'name' => 'Sessions',
                        'count' => $teacher->sessions_count,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $book_interview_count,
                    ],
                    //                    [
                    //                        'name' => 'quizzes',
                    //                        'count' => $teacher->quizzes_count,
                    //                    ],
                    [
                        'name' => 'Personal Interviews',
                        'count' => $personal_interview_count,
                    ],
                    [
                        'name' => 'Face-to-Face-Quiz-school',
                        'count' => $quran_quiz_pages,
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => $absence_quiz_pages,
                    ],

                ];

                //            $data =  $this->builder->whereId($id)
                //                ->appTeacherWithUser()
                //                ->firstOrFail();
                //
                //            $teacherStatistics = $this->builder->withCount(['classRooms','sessions','quranQuizzes', 'quizzes', 'interviews', 'notes'])
                //                ->findOrFail($id);
                //
                //            $result = (object) array_merge((array)$data, $teacherStatistics->toArray());
            } elseif ($propertyType === 'mosque') {

                $data = [
                    [
                        'name' => 'Students',
                        'count' => $studentsCount,
                    ],
                    [
                        'name' => 'Sessions',
                        'count' => $teacher->sessions_count,
                    ],
                    [
                        'name' => 'Quizzes',
                        'count' => $teacher->quizzes_count,
                    ],

                    [
                        'name' => 'Activities',
                        'count' => $teacher->activity_count,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $book_interview_count,
                    ],
                    [
                        'name' => 'Correct Arabic Reading Quiz',
                        'count' => $write_arabic_reading_quiz_pages,
                    ],
                    [
                        'name' => 'Personal Interviews',
                        'count' => $personal_interview_count,
                    ],
                    [
                        'name' => 'Face-to-Face Quiz',
                        'count' => $quran_quiz_pages,
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => $absence_quiz_pages,
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

    public function mobileGetTeacherClassRooms($params): object
    {
        try {

            $this->builder = $this->builder
                ->where('id', $params->teacherId)
                ->with('classRooms')
                ->with('classRooms.grade')
                ->with('classRooms.property');

            $data = collect(
                $this->all()
                    ->toArray()[0]['class_rooms']
            );

            if ($data->count() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data,
                );
            } elseif ($data->count() === 0) {
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

    public function mobileCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $user = $this->createObject($object['user'], $this->associate);

            if (!empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (isset($object['user']['image'])) {
                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path . '/' . $filename;
            }

            unset($object['user']);

            $teacher = $this->createObject($object, $this->model, false);
            $teacher->user()->associate($user);
            $user->save();
            $teacher->save();

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $teacher,
                ['Teacher Created Successfully']
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = $e->getMessage();

            if (Str::contains($errorMessage, 'users_username_unique')) {
                $errorMessage = 'Username already exists.';
            } elseif (Str::contains($errorMessage, 'users_email_unique')) {
                $errorMessage = 'Email address is already in use.';
            } elseif (Str::contains($errorMessage, 'users_identity_number_unique')) {
                $errorMessage = 'Identity number is already in use.';
            } else {
                $errorMessage = 'Something went wrong';
            }

            return $this->setResponse(
                parent::FAILED,
                null,
                ['Error' => $errorMessage]
            );
        }
    }

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $teacher = $this->builder->whereId($id)->firstOrFail();
            $user = $teacher->user;
            $user->fill($object['user']);

            //            $user->property_id = $object['user']['property_id'];
            //            $user->email = $object['user']['email'];
            //            $user->first_name = $object['user']['first_name'];
            //            $user->last_name = $object['user']['last_name'];
            //            $user->username = $object['user']['username'];
            //            $user->identity_number = $object['user']['identity_number'];
            //            $user->phone = $object['user']['phone'];
            //            $user->gender = $object['user']['gender'];
            //            $user->birth_date = $object['user']['birth_date'];
            //            $user->birth_place = $object['user']['birth_place'];
            //            $user->father_name = $object['user']['father_name'];
            //            $user->mother_name = $object['user']['mother_name'];
            //            $user->qr_code = $object['user']['qr_code'];
            //            $user->blood_type = $object['user']['blood_type'];
            //            $user->note = $object['user']['note'];
            //            $user->current_address = $object['user']['current_address'];
            //            $user->is_has_disease = $object['user']['is_has_disease'];
            //            $user->disease_name = $object['user']['disease_name'];
            //            $user->is_has_treatment = $object['user']['is_has_treatment'];
            //            $user->treatment_name = $object['user']['treatment_name'];
            //            $user->are_there_disease_in_family = $object['user']['are_there_disease_in_family'];
            //            $user->family_disease_note = $object['user']['family_disease_note'];
            //            $user->status = $object['user']['status'];

            if (!empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (!empty($object['user']['username'])) {
                $user->username = $object['user']['username'];
            }

            // Check if the image is present in the request
            if (!empty($object['user']['image'])) {
                $db_user = User::find($id);
                // Check if there's an old image

                if (!isset($user->image)) {
                    // Delete the old image file

                    $oldImagePath = public_path($db_user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid() . '.' . 'png';

                // Build the full path to the file
                $fullPath = public_path($path . '/' . $filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path . '/' . $filename;
            }

            $user->save();
            $teacher->fill($object);
            $teacher->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $teacher,
                ['Teacher Updated Successfully']
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            if (Str::contains($errorMessage, 'users_username_unique')) {
                $errorMessage = 'Username already exists.';
            } elseif (Str::contains($errorMessage, 'users_email_unique')) {
                $errorMessage = 'Email address is already in use.';
            } elseif (Str::contains($errorMessage, 'users_identity_number_unique')) {
                $errorMessage = 'Identity number is already in use.';
            } else {
                $errorMessage = 'Something went wrong';
            }

            return $this->setResponse(
                parent::FAILED,
                null,
                ['Error' => $errorMessage]
            );
        }
    }

    //    public function mobileTransferTeacher($object, $teacherId): object
    //    {
    //        try {
    //            DB::beginTransaction();
    //
    //            // Find the existing teacher
    //            $teacher = Teacher::with('classRooms')->find($teacherId);
    //            $user = $teacher->user;
    //
    //            // Default message when no specific message is set
    //            $message = [];
    //
    //            // Check if the teacher's current property is different from the new one
    //            if ($object['new_property_id'] && $user->property_id !== intval($object['new_property_id'])) {
    //                // Update the teacher's property_id
    //                $user->property_id = $object['new_property_id'];
    //                $user->save();
    //
    //                $message[] = "Teacher Transfer Successful to Property ID {$object['new_property_id']}";
    //
    //                // Check if $new_class_id is provided
    //                if (!empty($object['new_class_id'])) {
    //                    // Get the new class ID from the object
    //                    $newClassId = $object['new_class_id'];
    //
    //                    // Update left_at to now for all records with the same teacher ID and not already left
    //                    ClassRoomTeacher::where('teacher_id', $teacherId)
    //                        ->whereNull('left_at')
    //                        ->update(['left_at' => now()]);
    //
    //                    $classRoomTeacher = new ClassRoomTeacher();
    //                    $classRoomTeacher->class_room_id = $newClassId;
    //                    $classRoomTeacher->teacher_id = $teacherId;
    //                    $classRoomTeacher->joined_at = now();
    //                    $classRoomTeacher->save();
    //
    //                    $message[] = "Teacher Transfer Successful to Class Room ID {$object['new_class_id']}";
    //
    //
    //                } else {
    //                    // Teacher is already in the specified class
    //                    $message[] = "Teacher is already in the specified Class.";
    //                }
    //            }
    //            $message[] = "Teacher is already in the specified Property.";
    //
    //
    //            return $this->setResponse(
    //                parent::SUCCESS,
    //                $teacher,
    //                [$message]
    //            );
    //
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return $this->setResponse(
    //                parent::FAILED,
    //                null,
    //                ['Something went wrong']
    //            );
    //        }
    //    }

    public function mobileTransferTeacher($object, $teacherId): object
    {
        try {
            DB::beginTransaction();

            // Find the existing teacher
            $teacher = Teacher::with('classRooms')->find($teacherId);
            $user = $teacher->user;

            if (!empty($object['new_property_id'])) {

                if ($user->property_id !== intval($object['new_property_id'])) {

                    // Update the teacher's property_id
                    $user->property_id = $object['new_property_id'];
                    $user->save();

                    ClassRoomTeacher::where('teacher_id', $teacherId)
                        ->whereNull('left_at')
                        ->update(['left_at' => now()]);

                    $message[] = "Teacher Transferred Successfully to Property ID {$object['new_property_id']}";

                } else {
                    $message[] = 'Teacher is already in the specified Property.';

                }

                if (!empty($object['new_class_id'])) {

                    // Get the new class ID from the object
                    $newClassId = $object['new_class_id'];

                    // Check if there is no existing record for the same teacher ID and new class ID
                    $existingRecord = ClassRoomTeacher::where('teacher_id', $teacherId)
                        ->where('class_room_id', $newClassId)
                        ->first();

                    if (!$existingRecord) {
                        // No existing record, proceed with the transfer
                        // Update left_at to now for all records with the same teacher ID and not already left
                        ClassRoomTeacher::where('teacher_id', $teacherId)
                            ->whereNull('left_at')
                            ->update(['left_at' => now()]);

                        $classRoomTeacher = new ClassRoomTeacher();
                        $classRoomTeacher->class_room_id = $newClassId;
                        $classRoomTeacher->teacher_id = $teacherId;
                        $classRoomTeacher->joined_at = now();
                        $classRoomTeacher->save();

                        $message[] = "Teacher Transferred Successfully to Class ID {$object['new_class_id']}";
                    } else {
                        // Teacher is already in the specified class
                        $message[] = 'Teacher is already in the specified Property and Class.';
                    }

                } else {
                    // No new class ID provided, you can assume the teacher has no class assigned
                    // Handle this case as needed, for example, you can add the teacher to a default class
                    // or display an error message.
                    $message[] = 'No new class ID provided.';
                }

            } else {
                $message[] = 'You need to add property ID';
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $teacher,
                [$message]
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                ['Something went wrong']
            );
        }
    }

    public function mobileDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();

            // Delete related records in sessions table
            $obj->sessions()->delete();

            // Delete related records in class_room_teachers pivot table
            $obj->classRooms()->detach();

            // Delete related records in notes table
            $obj->notes()->delete();

            // Delete related records in interviews table
            $obj->interviews()->delete();

            // Delete related records in quran_quizzes table
            $obj->quranQuizzes()->delete();

            // Delete related records in quizzes table
            $obj->quizzes()->delete();

            // Delete related records in activities table
            $obj->activity()->delete();

            // Delete related records in rates table
            $obj->rates()->delete();

            $obj->delete();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['Teacher Deleted']
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
