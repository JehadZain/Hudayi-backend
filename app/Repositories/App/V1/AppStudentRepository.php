<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppStudent;
use App\Models\Infos\Activity;
use App\Models\Infos\Session;
use App\Models\Infos\Status;
use App\Models\Morphs\Address;
use App\Models\Morphs\Contact;
use App\Models\Morphs\ModelStatus;
use App\Models\Properties\ClassRoomStudent;
use App\Models\Properties\Property;
use App\Models\Users\Student;
use App\Models\Users\User;
use App\Repositories\Common\V1\StudentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppStudentRepository extends StudentRepository implements IAppStudent
{
    public function appAll($params = null): object
    {
        try {
            $this->builder = $this->builder->select(
                'id',
                'user_id',
                'parent_work',
                'family_members_count',
                'is_orphan',
                'parent_phone',
                'who_is_parent',
            )->with('user')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            if ($data->total() > 0) {
                // Calculate statistics for all students
                foreach ($data as $student) {

                    // Combine user and student info
                    $studentData  = [
                        'id' => $student->id,
                        'user_id' => $student->user_id,
                        'parent_work' => $student->parent_work,
                        'family_members_count' => $student->family_members_count,
                        'is_orphan' => $student->is_orphan,
                        'parent_phone' => $student->parent_phone,
                        'who_is_parent' => $student->who_is_parent,
                        'property_id' => $student->user->property_id,
                        'email' => $student->user->email,
                        'first_name' => $student->user->first_name,
                        'last_name' => $student->user->last_name,
                        'username' => $student->user->username,
                        'identity_number' => $student->user->identity_number,
                        'phone' => $student->user->phone,
                        'gender' => $student->user->gender,
                        'birth_date' => $student->user->birth_date,
                        'birth_place' => $student->user->birth_place,
                        'father_name' => $student->user->father_name,
                        'mother_name' => $student->user->mother_name,
                        'qr_code' => $student->user->qr_code,
                        'blood_type' => $student->user->blood_type,
                        'note' => $student->user->note,
                        'current_address' => $student->user->current_address,
                        'is_has_disease' => $student->user->is_has_disease,
                        'disease_name' => $student->user->disease_name,
                        'is_has_treatment' => $student->user->is_has_treatment,
                        'treatment_name' => $student->user->treatment_name,
                        'are_there_disease_in_family' => $student->user->are_there_disease_in_family,
                        'family_disease_note' => $student->user->family_disease_note,
                        'status' => $student->user->status,
                        'image' => $student->user->image,
                        'is_approved' => $student->user->is_approved,
                    ];



                    // Retrieve the class room ID for the student
                    $classRoom = $student->classRooms()->first();
                    $classRoomId = $classRoom ? $classRoom->id : null;

                    // Initialize counters
                    $totalSessions = 0;
                    $attendedSessionCount = 0;
                    $totalActivities = 0;
                    $attendedActivityCount = 0;
                    $quranQuizPages = 0;
                    $absenceQuizPages = 0;
                    $arabicReadingQuizPages = 0;
                    $bookInterviewCount = 0;
                    $personalInterviewCount = 0;

                    // Calculate sessions if classRoomId exists
                    if ($classRoomId) {
                        $totalSessions = Session::where('class_room_id', $classRoomId)->count();
                        $attendedSessionCount = $student->sessionAttendances()->count();

                        // Calculate activities
                        $totalActivities = Activity::where('class_room_id', $classRoomId)->count();
                        $attendedActivityCount = $student->activityParticipants()->count();
                    }

                    $unattendedSessionCount = max(0, $totalSessions - $attendedSessionCount);
                    $unattendedActivityCount = max(0, $totalActivities - $attendedActivityCount);

                    // Calculate Quran quiz pages
                    if ($student->quranQuizzes) {
                        $faceToFaceQuiz = $student->quranQuizzes()->where('exam_type', 'recitationFromQuran')->get();
                        $quranQuizPages = $this->getStudentExamPages($faceToFaceQuiz);

                        $absenceQuiz = $student->quranQuizzes()->where('exam_type', 'memorization')->get();
                        $absenceQuizPages = $this->getStudentExamPages($absenceQuiz);

                        $arabicReadingQuiz = $student->quranQuizzes()->where('exam_type', 'correctArabicReading')->get();
                        $arabicReadingQuizPages = $this->getStudentExamPages($arabicReadingQuiz);
                    }

                    // Calculate interview counts
                    if ($student->interviews) {
                        $bookInterviewCount = $student->interviews()->where('type', 'book')->count();
                        $personalInterviewCount = $student->interviews()->where('type', '<>', 'book')->count();
                    }

                    // Determine property type
                    $property = $student->user ? $student->user->property_id : null;
                    $propertyType = $property ? Property::whereId($property)->first()->property_type : null;

                    // Prepare statistics
                    $statistics = [
                        ['name' => 'Sessions', 'count' => $attendedSessionCount],
                        ['name' => 'Unattended Sessions', 'count' => $unattendedSessionCount],
                        ['name' => 'Activities', 'count' => $attendedActivityCount],
                        ['name' => 'Unattended Activities', 'count' => $unattendedActivityCount],
                        ['name' => 'Books', 'count' => $bookInterviewCount],
                        ['name' => 'Personal Interviews', 'count' => $personalInterviewCount],
                        ['name' => 'Face-to-Face Quiz', 'count' => $quranQuizPages],
                        ['name' => 'Absence Quiz', 'count' => $absenceQuizPages],
                        ['name' => 'Correct Arabic Reading Quiz', 'count' => $arabicReadingQuizPages],
                        ['name' => 'Quizzes', 'count' => $student->quizzes ? $student->quizzes()->count() : 0],
                        ['name' => 'Notes', 'count' => $student->notes ? $student->notes()->count() : 0],
                    ];

                    // Add statistics to the student data
                    $studentData['statistics'] = $statistics;

                    // Add the student data to the restructured data array
                    $restructuredData[] = $studentData;

                    $paginatedData = $filter->paginate($params->per_page ?? 15);

                    // If it's a school, modify the statistics
                    if ($propertyType === 'school') {
                        $statistics = array_filter($statistics, function($item) {
                            return !in_array($item['name'], ['Sessions', 'Unattended Sessions', 'Correct Arabic Reading Quiz']);
                        });
                        // Rename 'Face-to-Face Quiz' to 'Face-to-Face-Quiz-school' for schools
                        foreach ($statistics as &$item) {
                            if ($item['name'] === 'Face-to-Face Quiz') {
                                $item['name'] = 'Face-to-Face-Quiz-school';
                                break;
                            }
                        }
                    }
                }

                $pagination = [
                    'current_page' => $paginatedData->currentPage(),
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

                $responseData = array_merge(
                    ['students' => $restructuredData],
                    $pagination
                );

                return $this->setResponse(
                    parent::SUCCESS,
                    $responseData
                );
            } elseif ($data->total() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
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
                'user_id',
                'parent_work',
                'family_members_count',
                'is_orphan',
                'parent_phone',
                'who_is_parent',
            )->with([
                'user.certifications',
                'classRooms.grade:id,name,property_id',
                'classRooms.grade.property:id,name',
                'sessionAttendances.session',
                'sessionAttendances' => function ($query) {
                    $query->orderBy('session_id', 'desc');
                },
                'activityParticipants.activity.activityType',
                'activityParticipants.activity.participants.student.user:id,first_name,last_name,image',
                'activityParticipants.activity.teacher.user:id,first_name,last_name,image',
                'activityParticipants' => function ($query) {
                    $query->orderBy('id', 'desc');
                },
                'notes.teacher.user:id,first_name,last_name,image',
                'notes.admin.user:id,first_name,last_name,image',
                'notes' => function ($query) {
                    $query->orderBy('id', 'desc');
                },
                'interviews.teacher.user:id,first_name,last_name',
                'interviews' => function ($query) {
                    $query->orderBy('id', 'desc');
                },
                'quranQuizzes.teacher.user:id,first_name,last_name',
                'quranQuizzes' => function ($query) {
                    $query->orderBy('id', 'desc');
                },
                'quizzes.teacher.user:id,first_name,last_name',
                'quizzes' => function ($query) {
                    $query->orderBy('id', 'desc');
                },
            ])
                ->orderBy('id', 'desc')
                ->firstOrFail();

            $student = $data;

            // Get the student's latest active classroom enrollment
            $latestEnrollment = ClassRoomStudent::where('student_id', $student->id)
                ->whereNull('left_at')
                ->orderByDesc('joined_at')
                ->first();

            $classRoomId = $latestEnrollment->class_room_id;
            $enrollmentDate = $latestEnrollment->joined_at;

            // Calculate sessions
            $totalSessions = Session::where('class_room_id', $classRoomId)
                ->where('created_at', '>=', $enrollmentDate)
                ->count();
            $attendedSessionCount = $student->sessionAttendances()
                ->whereHas('session', function ($query) use ($classRoomId, $enrollmentDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('created_at', '>=', $enrollmentDate);
                })
                ->count();
            $unattendedSessionCount = max(0, $totalSessions - $attendedSessionCount);


            // Calculate activities
            $totalActivities = Activity::where('class_room_id', $classRoomId)
                ->where('created_at', '>=', $enrollmentDate)
                ->count();
            $attendedActivityCount = $student->activityParticipants()
                ->whereHas('activity', function ($query) use ($classRoomId, $enrollmentDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('created_at', '>=', $enrollmentDate);
                })
                ->count();
            $unattendedActivityCount = max(0, $totalActivities - $attendedActivityCount);

            // Calculate Quran quiz pages
            $faceToFaceQuiz = $student->quranQuizzes()->where('exam_type', 'recitationFromQuran')->get();
            $quranQuizPages = $this->getStudentExamPages($faceToFaceQuiz);

            $absenceQuiz = $student->quranQuizzes()->where('exam_type', 'memorization')->get();
            $absenceQuizPages = $this->getStudentExamPages($absenceQuiz);

            $arabicReadingQuiz = $student->quranQuizzes()->where('exam_type', 'correctArabicReading')->get();
            $arabicReadingQuizPages = $this->getStudentExamPages($arabicReadingQuiz);

            // Calculate interview counts
            $bookInterviewCount = $student->interviews()->where('type', 'book')->count();
            $personalInterviewCount = $student->interviews()->where('type', '<>', 'book')->count();

            // Determine property type
            $property = $student->user->property_id;
            $propertyType = Property::whereId($property)->first()->property_type;

            // Prepare statistics
            $data['statistics'] = [
//                ['name' => 'Sessions', 'count' => $attendedSessionCount],
//                ['name' => 'Unattended Sessions', 'count' => $unattendedSessionCount],
//                ['name' => 'Activities', 'count' => $attendedActivityCount],
//                ['name' => 'Unattended Activities', 'count' => $unattendedActivityCount],
                ['name' => 'Books', 'count' => $bookInterviewCount],
                ['name' => 'Personal Interviews', 'count' => $personalInterviewCount],
                ['name' => 'Face-to-Face Quiz', 'count' => $quranQuizPages],
                ['name' => 'Absence Quiz', 'count' => $absenceQuizPages],
                ['name' => 'Correct Arabic Reading Quiz', 'count' => $arabicReadingQuizPages],
                ['name' => 'Quizzes', 'count' => $student->quizzes()->count()],
                ['name' => 'Notes', 'count' => $student->notes()->count()],
            ];

            // If it's a school, remove irrelevant statistics
            if ($propertyType === 'school') {
                $data['statistics'] = array_filter($data['statistics'], function($item) {
                    return !in_array($item['name'], ['Sessions', 'Unattended Sessions', 'Correct Arabic Reading Quiz']);
                });
                // Rename 'Face-to-Face Quiz' to 'Face-to-Face-Quiz-school' for schools
                foreach ($data['statistics'] as &$item) {
                    if ($item['name'] === 'Face-to-Face Quiz') {
                        $item['name'] = 'Face-to-Face-Quiz-school';
                        break;
                    }
                }
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

    //    public function appById(string $id): object
    //    {
    //        try {
    //            $data = $this->builder->whereId($id)->select(
    //                'id',
    //                'user_id',
    //                'parent_work',
    //                'family_members_count',
    //                'is_orphan',
    //                'parent_phone',
    //                'who_is_parent',
    //            )
    //                ->with([
    //                    'user:id,property_id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,father_name,mother_name,qr_code,blood_type,note,current_address,is_has_disease,disease_name,is_has_treatment,treatment_name,are_there_disease_in_family,family_disease_note,status,image',
    //                    'user.certifications',
    //                    'classRooms.grade:id,name',
    //                    'sessionAttendances.session',
    //                    'activityParticipants.activity.activityType',
    //                    'activityParticipants.activity.participants.student.user:id,first_name,last_name,image',
    //                    'notes.teacher.user:id,first_name,last_name,image',
    //                    'notes.admin.user:id,first_name,last_name,image',
    //                    'interviews.teacher.user:id,first_name,last_name',
    //                    'quranQuizzes.teacher.user:id,first_name,last_name',
    //                    'quranQuizzes' => function ($query) {
    //                        $query->orderBy('id', 'desc'); // Order the teacher records in descending order by their ID
    //                    },
    //                   'quranQuizzes.teacher.user:id,first_name,last_name',
    //                    'quizzes.teacher.user:id,first_name,last_name',
    //                ])
    //                ->firstOrFail();
    //
    //            $student = Student::findOrFail($id);
    //
    //            // Retrieve attended session IDs
    //            $attendedSessionIds = $student->sessionAttendances->pluck('session_id');
    //
    //            // Retrieve unattended sessions
    //            $unattendedSessions = Session::whereNotIn('id', $attendedSessionIds)
    //                ->whereIn('class_room_id', $student->classrooms->pluck('id'))
    //                ->get();
    //
    //            // Retrieve attended activity IDs
    //            $attendedActivityIds = $student->activityParticipants->pluck('activity_id');
    //
    //            // Retrieve unattended activities
    //            $unattendedActivities = Activity::whereNotIn('id', $attendedActivityIds)
    //                ->whereIn('class_room_id', $student->classrooms->pluck('id'))
    //                ->get();
    //
    //            $data['unattended_sessions'] = $unattendedSessions;
    //            $data['unattended_activities'] = $unattendedActivities;
    //
    //            return $this->setResponse(
    //                parent::SUCCESS,
    //                $data,
    //            );
    //        } catch (\Exception $e) {
    //            return $this->setResponse(
    //                parent::NO_DATA,
    //                null,
    //                ["OBJECT_NOT_FOUND"]
    //            );
    //        }
    //    }

    public function appGetStudentGroups($params): object
    {
        try {
            $this->builder = $this->builder->where('id', $params->studentId)
                ->with('classRooms.property')
                ->with('classRooms.grade');
            $data = collect($this->all()->toArray());
            //            $data = collect($this->all()->toArray()[0]['class_room_students']);
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

    //    public function appCreateObject($object): object
    //    {
    //        try {
    //            DB::beginTransaction();
    //            $user = $this->createObject($object['user'], $this->associate);
    //            //morph contact
    ////            $contact = new Contact();
    ////            $contact->email = $object['contact']['email'];
    ////            $contact->phone = $object['contact']['phone'];
    ////            $contact->whatsapp = $object['contact']['whatsapp'];
    ////            $contact->facebook = $object['contact']['facebook'];
    ////            $contact->instagram = $object['contact']['instagram'];
    ////
    ////
    ////            $address = new Address();
    ////            $address->label = $object['address']['label'];
    ////            $address->country = $object['address']['country'];
    ////            $address->city = $object['address']['city'];
    ////            $address->state = $object['address']['state'];
    ////            $address->line_1 = $object['address']['line_1'];
    ////            $address->line_2 = $object['address']['line_2'];
    ////            $address->floor = $object['address']['floor'];
    ////            $address->flat = $object['address']['flat'];
    ////            $address->lat = $object['address']['lat'];
    ////            $address->long = $object['address']['long'];
    ////            $address->location_url = $object['address']['location_url'];
    //
    //            unset($object['user']);
    //
    ////            unset($object['contact']);
    ////            unset($object['address']);
    //
    //            $student = $this->createObject($object, $this->model, false);
    //            $student->user()->associate($user);
    //            $student->save();
    ////            $student->contacts()->save($contact);
    ////            $student->addresses()->save($address);
    //
    //            //status morph belongsTo
    ////            $status = Status::whereName('pending')->firstOrFail();
    ////            $newModelStatus = new ModelStatus();
    ////            $newModelStatus->status()->associate($status);
    ////            $student->statuses()->save($newModelStatus);
    //
    //            DB::commit();
    //
    //            return $this->setResponse(
    //                parent::SUCCESS,
    //                $student,
    //            );
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return $this->setResponse(
    //                parent::FAILED,
    //                null,
    //                [$e->getMessage()]
    //            );
    //        }
    //    }

    public function appCreateObject($object): object
    {
        try {
            DB::beginTransaction();
            $user = $this->createObject($object['user'], $this->associate);

            if (! empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (isset($object['user']['image'])) {
                $imageUrl = $object['user']['image'];
                $imageData = file_get_contents($imageUrl);
                $base64 = base64_encode($imageData);
                $path = 'storage/images/users';

                // Generate a unique filename for the image
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path.'/'.$filename;
            }

            unset($object['user']);

            $student = $this->createObject($object, $this->model, false);
            $student->user()->associate($user);
            $user->save();
            $student->save();

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $student,
                ['Student Created Successfully']
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

    //    public function appUpdateObject($object, $id): object
    //    {
    //        try {
    //            DB::beginTransaction();
    //            $student = $this->builder->whereId($id)->firstOrFail();
    //            $user = $student->user;
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
    //
    //            if (!empty($object['user']['password'])) {
    //                $user->password = Hash::make($object['user']['password']);
    //            }
    //
    //            // Check if the image is present in the request
    //            if (!empty($object['user']['image'])) {
    //                $image = $object->file('image');
    //                $path = Storage::putFile('public/images/users', $image);
    //                $user->image = $path;
    //            }
    //
    //            $student->user_id = $object['user_id'];
    //            $student->parent_work = $object['parent_work'];
    //            $student->family_members_count = $object['family_members_count'];
    //            $student->parent_phone = $object['parent_phone'];
    //            $student->who_is_parent = $object['who_is_parent'];
    //
    //            $user->save();
    //            $student->save();
    //
    //            DB::commit();
    //
    //            return $this->setResponse(
    //                parent::SUCCESS,
    //                $student,
    //                ['Student updated successfully']
    //            );
    //
    ////            DB::beginTransaction();
    ////            $obj = $this->builder->whereId($id)->firstOrFail();
    ////
    ////            $this->updateObject($obj->user, $object['user'], $this->associate);
    ////            unset($object['user']);
    ////
    ////            $obj->user_id = $object['user_id'];
    ////            $obj->parent_work = $object['parent_work'];
    ////            $obj->family_members_count = $object['family_members_count'];
    ////            $obj->parent_phone = $object['parent_phone'];
    ////            $obj->who_is_parent = $object['who_is_parent'];
    ////            $obj->save();
    ////            DB::commit();
    ////            return $this->setResponse(
    ////                parent::SUCCESS,
    ////                $obj,
    ////            );
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return $this->setResponse(
    //                parent::FAILED,
    //                null,
    //                [$e->getMessage()]
    //            );
    //        }
    //    }

    public function appUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();
            $student = $this->builder->whereId($id)->firstOrFail();
            $user = $student->user;
            $user->fill($object['user']);

            if (! empty($object['user']['password'])) {
                $user->password = Hash::make($object['user']['password']);
            }

            if (! empty($object['user']['username'])) {
                $user->username = $object['user']['username'];
            }

            // Check if the image is present in the request
            if (! empty($object['user']['image'])) {
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
                $filename = uniqid().'.'.'png';

                // Build the full path to the file
                $fullPath = public_path($path.'/'.$filename);

                // Save the base64-encoded image data to disk
                file_put_contents($fullPath, base64_decode($base64));

                // Store the path link in the database
                $user->image = $path.'/'.$filename;
            }

            $user->save();
            $student->fill($object);
            $student->save();

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $student,
                ['Student updated successfully']
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

    public function appTransferStudent($object, $studentId): object
    {
        try {
            DB::beginTransaction();

            // Find the existing student
            $student = Student::with('classRooms')->find($studentId);
            $user = $student->user;

            // Default message when no specific message is set
            //            $message[] = "";

            if (! empty($object['new_property_id'])) {
                //dd(($user->property_id !== intval($object['new_property_id'])) );
                if ($user->property_id !== intval($object['new_property_id'])) {

                    // Update the student's property_id
                    $user->property_id = $object['new_property_id'];
                    $user->save();

                    ClassRoomStudent::where('student_id', $studentId)
                        ->whereNull('left_at')
                        ->update(['left_at' => now()]);

                    $message[] = "Student Transferred Successfully to Property ID {$object['new_property_id']} ";

                } else {
                    $message[] = 'Student is already in the specified Property.';

                }

                if (! empty($object['new_class_id'])) {

                    // Get the new class ID from the object
                    $newClassId = $object['new_class_id'];

                    // Check if there is no existing record for the same student ID and new class ID
                    $existingRecord = ClassRoomStudent::where('student_id', $studentId)
                        ->where('class_room_id', $newClassId)
                        ->first();

                    if (! $existingRecord) {

                        // No existing record, proceed with the transfer
                        // Update left_at to now for all records with the same student ID and not already left
                        ClassRoomStudent::where('student_id', $studentId)
                            ->whereNull('left_at')
                            ->update(['left_at' => now()]);

                        $classRoomStudent = new ClassRoomStudent();
                        $classRoomStudent->class_room_id = $newClassId;
                        $classRoomStudent->student_id = $studentId;
                        $classRoomStudent->joined_at = now();
                        //                        dd($classRoomStudent);
                        $classRoomStudent->save();

                        $message[] = "Student Transferred Successfully to Class ID {$object['new_class_id']}";
                    } else {
                        // Student is already in the specified class
                        $message[] = 'Student is already in the specified Class.';
                    }

                } else {
                    // No new class ID provided, you can assume the student has no class assigned
                    // Handle this case as needed, for example, you can add the student to a default class
                    // or display an error message.
                    $message[] = 'No new class ID provided.';
                }

            } else {
                $message[] = 'You need to add property ID';
            }

            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $student,
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

    public function appDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();
            $obj = $this->builder->whereId($id)->firstOrFail();

            // Delete related records in class_rooms table
            $obj->classRooms()->detach();

            // Delete related records in session_attendances table
            $obj->sessionAttendances()->delete();

            // Delete related records in notes table
            $obj->notes()->delete();

            // Delete related records in interviews table
            $obj->interviews()->delete();

            // Delete related records in quran_quizzes table
            $obj->quranQuizzes()->delete();

            // Delete related records in quizzes table
            $obj->quizzes()->delete();

            // Delete related records in activity_participants table
            $obj->activityParticipants()->delete();

            // Delete the user object itself
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

    public function studentStatistics(string $id): object
    {
        try {
            $student = $this->builder->withCount(['quizzes', 'interviews', 'notes', 'sessionAttendances', 'activityParticipants'])->findOrFail($id);

            // Get the student's latest active classroom enrollment
            $latestEnrollment = ClassRoomStudent::where('student_id', $student->id)
                ->whereNull('left_at')
                ->orderByDesc('joined_at')
                ->first();

            $classRoomId = $latestEnrollment->class_room_id;
            $enrollmentDate = $latestEnrollment->joined_at;

            // Get all sessions belonging to the class room
            $totalSessions = Session::where('class_room_id', $classRoomId)
                ->where('created_at', '>=', $enrollmentDate)
                ->count();;
            $attendedSessionCount = $student->sessionAttendances()
                ->whereHas('session', function ($query) use ($classRoomId, $enrollmentDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('created_at', '>=', $enrollmentDate);
                })->count();
            $unattended_session_count = max(0, $totalSessions - $attendedSessionCount); // Ensure non-negative count

            // Get all activities belonging to the class room after the enrollment date
            $totalActivities = Activity::where('class_room_id', $classRoomId)
                ->where('created_at', '>=', $enrollmentDate)
                ->count();
            $attendedActivityCount = $student->activityParticipants()
                ->whereHas('activity', function ($query) use ($classRoomId, $enrollmentDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('created_at', '>=', $enrollmentDate);
                })
                ->count();
            $unattended_activity_count = max(0, $totalActivities - $attendedActivityCount); // Ensure non-negative count

            $face_to_face_quiz = $student->quranQuizzes()
                ->where('exam_type', 'recitationFromQuran')
                ->get();
            $quran_quiz_pages = $this->getStudentExamPages($face_to_face_quiz);

            $absence_quiz = $student->quranQuizzes()
                ->where('exam_type', 'memorization')
                ->get();
            $absence_quiz_pages = $this->getStudentExamPages($absence_quiz);

            $write_arabic_reading_quiz = $student->quranQuizzes()
//                ->where('score', '>', 25)
                ->where('exam_type', 'correctArabicReading')
                ->get();
            $write_arabic_reading_quiz_pages = $this->getStudentExamPages($write_arabic_reading_quiz);

            // Retrieve interviews where type is 'book' within the previous month
            $book_interview_count = $student->interviews()
                ->where('type', 'book')
                ->count();

            // Retrieve interviews where type is not 'book' within the previous month
            $personal_interview_count = $student->interviews()
                ->where('type', '<>', 'book')
                ->count();

            $property = $student->user->property_id;
            $propertyType = Property::whereId($property)->first()->property_type;

            if ($propertyType === 'school') {

                $data = [
                    [
                        'name' => 'Activities',
                        'count' => $student->activity_participants_count,
                    ],
                    [
                        'name' => 'Unattended Activities',
                        'count' => $unattended_activity_count,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $book_interview_count,
                    ],
                    [
                        'name' => 'Personal Interviews',
                        'count' => $personal_interview_count,
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => $absence_quiz_pages,
                    ],
                    [
                        'name' => 'Face-to-Face-Quiz-school',
                        'count' => $quran_quiz_pages,
                    ],
                    [
                        'name' => 'Notes',
                        'count' => $student->notes_count,
                    ],

                ];
            } elseif ($propertyType === 'mosque') {
                $data = [
                    [
                        'name' => 'Sessions',
                        'count' => $student->session_attendances_count,
                    ],
                    [
                        'name' => 'Unattended Sessions',
                        'count' => $unattended_session_count,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $student->activity_participants_count,
                    ],
                    [
                        'name' => 'Unattended Activities',
                        'count' => $unattended_activity_count,
                    ],
                    [
                        'name' => 'Books',
                        'count' => $book_interview_count,
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
                    [
                        'name' => 'Correct Arabic Reading Quiz',
                        'count' => $write_arabic_reading_quiz_pages,
                    ],
                    [
                        'name' => 'Quizzes',
                        'count' => $student->quizzes_count,
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
                [$e->getMessage()]
            );
        }
    }

    public function appGetAllOrgStudents($params = null): object
    {
        try {

            // Fetch the students with user information
            $studentsQuery = $this->builder->appStudentWithUser();

            $searchWithUser = request()->input('searchWithUser');

            if (!empty($searchWithUser)) {
                $studentsQuery = $studentsQuery->where(function ($query) use ($searchWithUser) {
                    $query->orWhereHas('user', function ($subquery) use ($searchWithUser) {
                        $subquery->where('first_name', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('last_name', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('email', 'LIKE', '%' . $searchWithUser . '%');
                        $subquery->orWhere('username', 'LIKE', '%' . $searchWithUser . '%');
                    });
                });
            }

            // Paginate the transformed data
            $paginatedData = $studentsQuery->paginate();

            if ($paginatedData->isEmpty()) {
                return $this->setResponse(
                    parent::NO_DATA,
                    null,
                    ['NO_DATA']
                );
            }

            // Select specific attributes for the response
            $selectedAttributesData = $paginatedData->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => $student->user_id,
                    'name' => $student->user?->first_name.' '.$student->user?->last_name,
                    'status' => $student?->user?->status,
                    'identity_number' => $student?->user?->identity_number,
                    'image' => $student?->user?->image,
                    'gender' => $student?->user?->gender,
                ];
            });

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

            // Use the transformedStudents array in the response
            return $this->setResponse(
                parent::SUCCESS,
                $data, // Use the modified data with selected attributes
                ['total' => $paginatedData->total()]
            );

            // Fetch the students with user information
            //            $this->builder = $this->builder->appStudentWithUser();

            // Transform the student data
            //            $transformedStudents = $students->map(function ($student) {
            //                return [
            //                    'id' => $student->id,
            //                    'user_id' => $student->user_id,
            //                    'name' => $student->user?->first_name . " " . $student->user?->last_name,
            //                    'status' => $student?->user?->status,
            //                    'identity_number' => $student?->user?->identity_number,
            //                    'image' => $student?->user?->image,
            //                    'gender' => $student?->user?->gender,
            //                ];
            //            })->sortBy('id')->values()->paginate();

            // Apply any filtering if required
            //            $filter = $this->filter($params);

            // Paginate the transformed data
            //            $data = $filter->paginate();

            // Use the transformedStudents array in the response
            //            return $this->setResponse(
            //                parent::SUCCESS,
            //                $data // Use the transformedStudents array here
            //            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                [$e->getMessage()]
            );
        }
    }
}
