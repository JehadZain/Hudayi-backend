<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileProperty;
use App\Models\Properties\Property;
use App\Models\User;
use App\Models\Users\Admin;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Repositories\Common\V1\PropertyRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MobilePropertyRepository extends PropertyRepository implements IMobileProperty
{
    /**
     * Retrieve property data with related information and counts.
     *
     * @param null $params Additional parameters (optional).
     * @return object Response object with property data, counts, and appropriate status.
     *
     * @throws Exception If something goes wrong during the process.
     */
    public function mobileAll($params = null): object
    {
        try {
            // Retrieve property data with selected fields and related models
            $this->builder = $this->builder->select(
                'id',
                'name',
                'capacity',
                'property_type',
                'branch_id',
                'description',
                'email',
                'phone',
                'whatsapp',
                'facebook',
                'instagram',
                'location',
                'image'
            )->with('grades:id,name,description,property_id')
                ->with('propertyAdmins.admin.user:id,first_name,last_name,image,is_approved')
                ->orderBy($params->orderBy, $params->direction);

            $filter = $this->filter($params);
            $data = $filter->paginate();

            // Calculate mosque and school counts
            $mosqueCount = $data->where('property_type', 'mosque')->count();
            $schoolCount = $data->where('property_type', 'school')->count();
            $hints = ['mosque_count' => $mosqueCount, 'school_count' => $schoolCount];

            if ($data->total() > 0) {
                return $this->setResponse(
                    parent::SUCCESS,
                    $data,
                    $hints
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
            // Return failed response with error message
            return $this->setResponse(parent::FAILED, null, [$e->getMessage()]);
        }
    }

    public function mobileById(string $id): object
    {
        try {
            $data = $this->builder->whereId($id)
                ->select(
                    'id',
                    'name',
                    'capacity',
                    'property_type',
                    'branch_id',
                    'description',
                    'email',
                    'phone',
                    'whatsapp',
                    'facebook',
                    'instagram',
                    'location',
                    'image'

                )->with('grades:id,name,description,property_id')
                ->with('propertyAdmins.admin.user:id,first_name,last_name,image,is_approved')
//                ->with('subjects.books')
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

    public function propertyClassRooms(string $id, $params = null): object
    {
        try {
            //            dd($this->builder->whereId($id)->get());
            $data = $this->builder->whereId($id)->with('grades:id,name,property_id')
                ->with('grades.classRooms')->get()
                ->map(function ($item) {
                    $formattedData = [];
                    //                    dd($item);
                    foreach ($item->grades as $grade) {
                        foreach ($grade->classRooms as $classRoom) {
                            $formattedData[] = $grade->name . ' - ' . $grade->id . ' / ' . $classRoom->name . ' - ' . $classRoom->id;
                        }
                    }

                    return $formattedData;
                })
                ->flatten(); // Flatten the nested arrays

            //            $filter = $this->filter($params);
            //            $data = $filter->paginate();

            if ($this->builder->count() > 0) {
                //                $data = $filter->paginate();
                return $this->setResponse(parent::SUCCESS, $data);
            } elseif ($this->builder->count() === 0) {
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
            } else {
                throw new Exception('SOMETHING_WENT_WRONG');
            }
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function mobileGetAllMosques($params = null): object
    {
        try {

            $this->builder = $this->builder->where('property_type', 'mosque')->select(
                'id',
                'name',
                'capacity',
                'property_type',
                'branch_id',
                'description',
                'email',
                'phone',
                'whatsapp',
                'facebook',
                'instagram',
                'location',
                'image'

            )->with('grades:id,name,description,property_id')
                ->with('propertyAdmins.admin.user:id,first_name,last_name,image,is_approved');

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

    public function mobileGetAllSchools($params = null): object
    {
        try {
            $this->builder = $this->builder->where('property_type', 'school')->select(
                'id',
                'name',
                'capacity',
                'property_type',
                'branch_id',
                'description',
                'email',
                'phone',
                'whatsapp',
                'facebook',
                'instagram',
                'location',
                'image'

            )->with('grades:id,name,description,property_id')
                ->with('propertyAdmins.admin.user:id,first_name,last_name,image,is_approved');

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

    /**
     * Retrieve statistics for all properties including grades, classrooms, students, teachers, sessions, and activities.
     *
     * @param null $params Additional parameters (optional).
     * @return object Response object with property statistics and appropriate status.
     *
     * @throws Exception If something goes wrong during the process.
     */
    public function allPropertyStatistics($params = null): object
    {
        try {
            // Get all properties with their related models and counts
            $properties = Property::with(['grades' => function ($query) {
                $query->with(['classRooms' => function ($query) {
                    $query->withCount(['classRoomStudents', 'classRoomTeachers', 'sessions', 'activities']);
                }]);
            }])->get();

            // Get the start and end dates for the previous month
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();

            // Prepare the data to be returned
            $data = [];
            foreach ($properties as $property) {
                $classroomsData = [];

                if ($property->grades->isEmpty()) {
                    // Set default counts to 0 if there are no grades
                    $gradesCount = 0;
                    $classroomsCount = 0;
                    $approvedStudentsCount = 0;
                    $pendingStudentsCount = 0;
                    $teachersCount = 0;
                    $sessionsCount = 0;
                    $activitiesCount = 0;
                } else {
                    // Calculate the counts based on grades and class rooms
                    $gradesCount = $property->grades->count();
                    $classroomsCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->count();
                    });
                    $approvedStudentsCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->sum(function ($classroom) {
                            return $classroom->classRoomStudents->where('is_approved', 1)->count();
                        });
                    });
                    $pendingStudentsCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->sum(function ($classroom) {
                            return $classroom->classRoomStudents->where('is_approved', 0)->count();
                        });
                    });
                    $teachersCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->sum('classRoomTeachers_count');
                    });
                    $sessionsCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->sum('sessions_count');
                    });
                    $activitiesCount = $property->grades->sum(function ($grade) {
                        return $grade->classRooms->sum('activities_count');
                    });

                    // Prepare the classrooms data for each grade
                    foreach ($property->grades as $grade) {
                        foreach ($grade->classRooms as $classroom) {
                            $classroomsData[] = [
                                'id' => $classroom->id,
                                'name' => $classroom->name,
                                'class_stat' => [
                                    [
                                        'name' => 'students',
                                        'count' => $classroom->classRoomStudents_count,
                                    ],
                                    [
                                        'name' => 'sessions',
                                        'count' => $classroom->sessions_count,
                                    ],
                                    [
                                        'name' => 'activities',
                                        'count' => $classroom->activities_count,
                                    ],
                                ],
                            ];
                        }
                    }
                }

                // Prepare the property data with statistics and classrooms
                $data[] = [
                    'id' => $property->id,
                    'name' => $property->name,
                    'property_stat' => [
                        [
                            'name' => 'grades',
                            'count' => $gradesCount,
                        ],
                        [
                            'name' => 'classrooms',
                            'count' => $classroomsCount,
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
                            'name' => 'teachers',
                            'count' => $teachersCount,
                        ],
                        [
                            'name' => 'sessions',
                            'count' => $sessionsCount,
                        ],
                        [
                            'name' => 'activities',
                            'count' => $activitiesCount,
                        ],
                    ],
                    'classrooms' => $classroomsData,
                ];
            }

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );
        } catch (Exception $e) {
            // Return failed response with error message
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

    public function mobilePropertyStatistics(string $id): object
    {
        try {

            // Get the property with its grades, classrooms, sessions, and students
            $property = Property::with(['grades' => function ($query) {
                $query->with(['classRooms' => function ($query) {
                    $query->with(['sessions', 'classRoomStudents.student', 'classRoomTeachers.teacher']);
                }]);
            }])->findOrFail($id);

            // Calculate the start and end dates for the last month
            //            $lastMonthStart = now()->subMonth()->startOfMonth();
            //            $lastMonthEnd = now()->subMonth()->endOfMonth();

            //            dd($lastMonthStart);

            // Filter the grades within the last month
            //            $lastMonthGrades = $property->grades()->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->get();
            //            $gradesCount = $lastMonthGrades->count();

            //          Get the counts of each model in the property
            $gradesCount = $property->grades->count();
            $classroomsCount = $property->grades->sum(function ($grade) {
                return $grade->classRooms->count();
            });
            $approvedStudentsCount = $property->grades->sum(function ($grade) {
                return $grade->classRooms->sum(function ($classroom) {
                    return $classroom->classRoomStudents()
                        ->join('users', 'class_room_students.student_id', '=', 'users.id')
                        ->where('users.is_approved', 1)
                        ->count();
                });
            });

            $pendingStudentsCount = $property->grades->sum(function ($grade) {
                return $grade->classRooms->sum(function ($classroom) {
                    return $classroom->classRoomStudents()
                        ->join('users', 'class_room_students.student_id', '=', 'users.id')
                        ->where('users.is_approved', 0)
                        ->count();
                });
            });


            $teachersCount = Teacher::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);
            })->count();

            $sessionsCount = $property->grades->sum(function ($grade) {
                return $grade->classRooms->sum(function ($classroom) {
                    return $classroom->sessions?->count();

                });
            });

            $activityCount = $property->grades->sum(function ($grade) {
                return $grade->classRooms->sum(function ($classroom) {
                    //                    dd($classroom?->activities,$classroom->activities?->count());
                    return $classroom->activities?->count();
                });
            });

            $usersCount = User::where('property_id', $id)->count();

            // Get the students for the property
            $students = Student::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);
            })->get();

            $studentStatistics = [
                'face_to_face_quiz_pages' => 0,
                'absence_quiz_pages' => 0,
                'write_arabic_reading_quiz_pages' => 0,
                'quizzes_count' => 0,
                'interviews_count' => 0,
                'book_interviews_count' => 0,
                'personal_interviews_count' => 0,
            ];

            // Calculate student statistics
            foreach ($students as $student) {
                // Calculate face to face quiz pages
                $faceToFaceQuiz = $student->quranQuizzes
//                    ->where('score', '>', 25)
                    ->where('exam_type', 'recitationFromQuran');
                $studentStatistics['face_to_face_quiz_pages'] += $this->getStudentExamPages($faceToFaceQuiz);

                // Calculate absence quiz pages
                $absenceQuiz = $student->quranQuizzes
//                    ->where('score', '>', 25)
                    ->where('exam_type', 'memorization');
                $studentStatistics['absence_quiz_pages'] += $this->getStudentExamPages($absenceQuiz);

                // Calculate write arabic reading quiz pages
                $writeArabicReadingQuiz = $student->quranQuizzes
//                    ->where('score', '>', 25)
                    ->where('exam_type', 'correctArabicReading');
                $studentStatistics['write_arabic_reading_quiz_pages'] += $this->getStudentExamPages($writeArabicReadingQuiz);

                // Count quizzes
                $studentStatistics['quizzes_count'] += $student->quizzes_count;

                // Count interviews
                //                $studentStatistics['interviews_count'] += $student->interviews_count;

                $personalInterviews = $student->interviews->where('type', '<>', 'book');
                $studentStatistics['personal_interviews_count'] += count($personalInterviews);


                // Count interviews where type is "book"
                $bookInterviews = $student->interviews->where('type', 'book');
                $studentStatistics['book_interviews_count'] += count($bookInterviews);
            }
//            dd($studentStatistics['book_interviews_count']);

            $propertyType = $property->property_type;
            if ($propertyType === 'school') {
                // Prepare the data to be returned
                $data = [
                    [
                        'name' => 'Grades',
                        'count' => $gradesCount,
                    ],
                    [
                        'name' => 'Classrooms',
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
                    //                    [
                    //                        'name' => 'Sessions',
                    //                        'count' => $sessionsCount,
                    //                    ],
                ];

                $studentStatisticsData = [
                    [
                        'name' => 'Books',
                        'count' => $studentStatistics['book_interviews_count'],
                    ],
                    [
                        'name' => 'Activities',
                        'count' => $activityCount,
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
                    //                    [
                    //                        'name' => 'Correct Arabic Reading Quiz',
                    //                        'count' => $studentStatistics['write_arabic_reading_quiz_pages']
                    //                    ],
                ];

            } elseif ($propertyType === 'mosque') {
                $data = [
                    [
                        'name' => 'Classrooms',
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
                        'count' => $activityCount,
                    ],
                    //                    [
                    //                        'name' => 'Sessions',
                    //                        'count' => $sessionsCount,
                    //                    ],
                ];

                $studentStatisticsData = [
                    [
                        'name' => 'Books',
                        'count' => $studentStatistics['book_interviews_count'],
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
            $data = array_merge($data, $studentStatisticsData);

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

    public function mobileGetAllPropertyStudents(string $id, $params = null): object
    {
        try {

            $studentsQuery = Student::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);
            })->with('user');

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

            // Apply the filter using the provided method
            $paginatedData = $studentsQuery->paginate();

            if ($paginatedData->isEmpty()) {
                return $this->setResponse(
                    parent::NO_DATA,
                    null,
                    ['NO_DATA']
                );
            }

            $selectedAttributesData = $paginatedData->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => $student->user_id,
                    'name' => $student->user?->first_name . ' ' . $student->user?->last_name,
                    'image' => $student->user?->image,
                    'gender' => $student->user?->gender,
                    'status' => $student->user?->status,
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

            return $this->setResponse(
                parent::SUCCESS,
                $data
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function mobileGetAllPropertyTeachers(string $id): object
    {
        try {

            $teachers = Teacher::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);
            })->with('user')->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'user_id' => $teacher->user_id,
                        'name' => $teacher->user?->first_name . ' ' . $teacher->user?->last_name,
                        'image' => $teacher->user?->image,
                        'is_approved' => $teacher->user?->is_approved,
                        'status' => $teacher->user?->status,
                        'role' => 'teacher',
                    ];
                });

            $admins = Admin::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);

            })->with('user')->get()
                ->map(function ($admin) {
                    return [
                        'id' => $admin->id,
                        'user_id' => $admin->user_id,
                        'name' => $admin->user?->first_name . ' ' . $admin->user?->last_name,
                        'image' => $admin->user?->image,
                        'is_approved' => $admin->user?->is_approved,
                        'role' => 'admin',
                    ];
                });

            $users = $teachers->concat($admins);
            $users = $users->sortBy('is_approved')->values()->all();

            return $this->setResponse(
                parent::SUCCESS,
                $users,
            );
        } catch (\Exception $e) {
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }

    public function getStudentsWithoutClassroomByPropertyId(string $id): object
    {
        try {
            // Retrieve all students based on property_id
//            $students = Student::whereHas('user', function ($query) use ($id) {
//                // Filter students belonging to a specific property
//                $query->where('property_id', $id);
//            })
//                // Eager load classroom associations
//                ->with(['classRooms'])
//                ->get()
//                ->map(function ($student) {
//                    // Determine the total count of classrooms and the count of left or deleted classrooms
//                    $total_classrooms = $student->classRooms->count();
//                    $left_or_deleted_classrooms = $student->classRooms->filter(function ($classRoom) {
//                        return !is_null($classRoom->pivot->left_at) || !is_null($classRoom->pivot->deleted_at);
//                    })->count();
//
//                    // Include student if all classrooms are left or deleted, or if they have no classroom associations
//                    if ($total_classrooms == $left_or_deleted_classrooms || $total_classrooms == 0) {
//                        return [
//                            'id' => $student->id,
//                            'user_id' => $student->user_id,
//                            'first_name' => $student->user?->first_name,
//                            'last_name' => $student->user?->last_name,
//                        ];
//                    }
//                })
//                ->filter()
//                ->values(); // Ensure the collection is reindexed


            // Retrieve all students based on property_id
            $students = Student::whereHas('user', function ($query) use ($id) {
                // Filter students belonging to a specific property
                $query->where('property_id', $id);
            })
                // Eager load classroom associations
                ->with('classRooms')
                ->get()
                ->map(function ($student) {
                    // Determine the total count of classrooms
                    $total_classrooms = $student->classRooms->count();

                    // Count classrooms where the student has left or the classroom is deleted
                    $left_or_deleted_classrooms = $student->classRooms->reduce(function ($carry, $classRoom) {
                        // Check if the classroom is either left or deleted and increment the count
                        return $carry + (!is_null($classRoom->pivot->left_at) || !is_null($classRoom->pivot->deleted_at) ? 1 : 0);
                    }, 0);



                    // Check if the student has no active classrooms
                    if ($total_classrooms == 0 || $total_classrooms == $left_or_deleted_classrooms) {
                        return [
                            'id' => $student->id,
                            'user_id' => $student->user_id,
                            'first_name' => $student->user?->first_name,
                            'last_name' => $student->user?->last_name,
                        ];
                    }

                    // If not matching the condition, return null to be filtered out
                    return null;
                })
                ->filter() // Remove null entries
                ->values(); // Reindex the collection




//            $students = Student::whereHas('user', function ($query) use ($id) {
//                $query->where('property_id', $id);
//            })
//                ->with('classRooms')
//                ->get()
////                ->filter(function ($student) {
////                    return $student->classRooms->isEmpty() || $student->classRooms->every(function ($classRoom) {
////                            return $classRoom->pivot->left_at !== null || $classRoom->pivot->deleted_at !== null;
////                        });
////                })
//                ->filter(function ($student) {
//                    return $student->classRooms->every(function ($classRoom) {
//                        return $classRoom->pivot->joined_at !== null;
//                    });
//                })
//                ->map(function ($student) {
//                    return [
//                        'id' => $student->id,
//                        'user_id' => $student->user_id,
//                        'first_name' => $student->user?->first_name,
//                        'last_name' => $student->user?->last_name,
//                        'has_active_classroom' => $student->classRooms->contains(function ($classRoom) {
//                            return $classRoom->pivot->left_at === null && $classRoom->pivot->deleted_at === null;
//                        }),
//                    ];
//                })
//                ->reject(function ($student) {
//                    return $student['has_active_classroom'];
//                })
//                ->values();
            // Return the filtered and transformed students' data with a success response



//            $students = Student::whereHas('user', function ($query) use ($id) {
//                $query->where('property_id', $id);
//            })->get();
//
//            $inactiveStudents = collect();
//
//            foreach ($students as $student) {
//                $inactiveClassRooms = $student->classRooms()->whereHas('classRoomStudents', function ($query) {
//                    $query->whereNotNull('left_at');
//                })->exists();
//
//                if ($inactiveClassRooms) {
//                    $inactiveStudents->push([
//                        'id' => $student->id,
//                        'user_id' => $student->user_id,
//                        'first_name' => $student->user?->first_name,
//                        'last_name' => $student->user?->last_name,
//                    ]);
//                }
//            }
//
//            $allStudents = $students->whereIn('id', $inactiveStudents->pluck('id'))
//                ->map(function ($student) {
//                    return [
//                        'id' => $student->id,
//                        'user_id' => $student->user_id,
//                        'first_name' => $student->user?->first_name,
//                        'last_name' => $student->user?->last_name,
//                    ];
//                });







            return $this->setResponse(
                parent::SUCCESS,
                $students,
            );
        } catch (\Exception $e) {
            // Handle any exceptions by returning a no data response
            return $this->setResponse(
                parent::NO_DATA,
                null,
                ['OBJECT_NOT_FOUND']
            );
        }
    }



    public function getTeachersWithoutClassroomByPropertyId(string $id): object
    {
        try {
//            $teachers = Teacher::whereHas('user', function ($query) use ($id) {
//                $query->where('property_id', $id);
//            })->where(function ($query) {
//                $query->whereDoesntHave('classRooms') // Check if teacher doesn't belong to any classroom
//                ->orWhereHas('classRooms', function ($subQuery) {
//                    $subQuery->whereNotNull('class_room_teachers.left_at');
//                    // ->orWhereNotNull('class_room_teachers.deleted_at'); // Check if left_at is not null in the pivot table for all classrooms
//                });
//            })->get()

            $teachers = Teacher::whereHas('user', function ($query) use ($id) {
                $query->where('property_id', $id);
            })->where(function ($query) {
                $query->whereDoesntHave('classRooms') // Check if teacher doesn't belong to any classroom
                ->orWhere(function ($subQuery) {
                    $subQuery->whereHas('classRooms', function ($innerSubQuery) {
                        $innerSubQuery->whereNotNull('class_room_teachers.left_at');
                    })->whereDoesntHave('classRooms', function ($innerSubQuery) {
                        $innerSubQuery->whereNull('class_room_teachers.left_at');
                    });
                });
            })->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'user_id' => $teacher->user_id,
                        'first_name' => $teacher->user?->first_name,
                        'last_name' => $teacher->user?->last_name,
                    ];
                });

            return $this->setResponse(
                parent::SUCCESS,
                $teachers,
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
            $data = $object->except('image');
            $obj->fill($data);

            if ($object->hasFile('image')) {
                $image = $object->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'images/properties/' . $filename;

                // Compress and save the image
                $img = Image::make($image->getRealPath());
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::disk('public')->put($path, (string) $img->encode());

                // The path to be stored in DB should be accessible from the web
                $obj->image = 'storage/' . $path;
            }

            $obj->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['Property Created Successfully'],

            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                ["Couldn't Create Property: " . $e->getMessage()],

            );
        }
    }

    public function mobileUpdateObject($object, $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $data = $object->except('image');
            $obj->fill($data);

            if ($object->hasFile('image')) {
                // Delete the old image if it exists
                if ($obj->image && Storage::disk('public')->exists(str_replace('storage/', '', $obj->image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $obj->image));
                }

                $image = $object->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'images/properties/' . $filename;

                // Compress and save the new image
                $img = Image::make($image->getRealPath());
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::disk('public')->put($path, (string) $img->encode());

                // The path to be stored in DB should be accessible from the web
                $obj->image = 'storage/' . $path;
            }

            $obj->save();
            DB::commit();

            return $this->setResponse(
                parent::SUCCESS,
                $obj,
                ['Property Updated Successfully'],

            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->setResponse(
                parent::FAILED,
                null,
                ["Couldn't Update Property: " . $e->getMessage()],

            );
        }
    }

    public function mobileDeleteObject(string $id): object
    {
        try {
            DB::beginTransaction();

            $obj = $this->builder->whereId($id)->firstOrFail();
            $obj?->propertyAdmins()->delete();

            $grades = $obj->grades;
            foreach ($grades as $grade) {
                $grade->classRooms()->delete();

                $classRooms = $grade->classRooms;
                foreach ($classRooms as $classRoom) {
                    $classRoom->sessions()->delete();
                    $classRoom->classRoomTeachers()->delete();
                    $classRoom->classRoomStudents()->delete();
                    $classRoom->activities()->delete();
                    $classRoom->calendars()->delete();
                    $classRoom->books()->delete();
                }
            }
            $obj?->grades()->delete();

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
                ['Object Not Found']

            );
        }
    }
}
