<?php

namespace App\Repositories\App\V1;

use App\Interfaces\App\V1\IAppAnalytics;
use App\Models\Branch;
use App\Models\Infos\Activity;
use App\Models\Infos\Session;
use App\Models\Interview;
use App\Models\Properties\ClassRoom;
use App\Models\Properties\ClassRoomStudent;
use App\Models\Properties\Property;
use App\Models\Quiz;
use App\Models\QuranQuiz;
use App\Models\SessionAttendance;
use App\Models\Users\Admin;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Models\Users\User;
use App\Repositories\Common\V1\AnalyticsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppAnalyticsRepository extends AnalyticsRepository implements IAppAnalytics
{

    /**
     * Retrieve property data with various counts and filtering.
     *
     * @param null $params Additional parameters (optional).
     * @return object Response object with property data and appropriate status.
     *
     * @throws Exception If something goes wrong during the process.
     */
    public function appAll($params = null): object
    {
        try {
            // Retrieve property data with related grades and class rooms
            $data = Property::select('id', 'name')
                ->with(['grades' => function ($query) {
                    $query->select('id', 'name', 'property_id')
                        ->with(['classRooms' => function ($query) {
                            $query->withCount(['sessions', 'classRoomStudents', 'activities']);
                        }]);
                }])
                ->get(['id', 'name']);

            // Calculate various counts and filter data
            foreach ($data as $property) {
                // Calculate grade count
                $property->grade_count = $property->grades->count();

                // Calculate classroom count
                $property->classroom_count = $property->grades->sum(function ($grade) {
                    return $grade->classRooms->count();
                });

                // Calculate total sessions count for all class rooms within the property
                $property->sessions_count = $property->grades->sum(function ($grade) {
                    return $grade->classRooms->sum('sessions_count');
                });

                // Calculate approved students count for all class rooms within the property
                $property->approved_students_count = $property->grades->sum(function ($grade) {
                    return $grade->classRooms->sum(function ($classRoom) {
                        return $classRoom->classRoomStudents()
                            ->whereHas('student.user', function ($query) {
                                $query->where('is_approved', 1);
                            })
                            ->count();
                    });
                });

                // Calculate unapproved students count for all class rooms within the property
                $property->unapproved_students_count = $property->grades->sum(function ($grade) {
                    return $grade->classRooms->sum(function ($classRoom) {
                        return $classRoom->classRoomStudents()
                            ->whereHas('student.user', function ($query) {
                                $query->where('is_approved', 0);
                            })
                            ->count();
                    });
                });

                // Calculate total activities count for all class rooms within the property
                $property->activities_count = $property->grades->sum(function ($grade) {
                    return $grade->classRooms->sum('activities_count');
                });

                // Hide certain fields within grades and class rooms
                foreach ($property->grades as $grade) {
                    $grade->makeHidden('property_id');

                    foreach ($grade->classRooms as $classRoom) {
                        $classRoom->makeHidden([
                            'capacity',
                            'image',
                            'is_approved',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ]);
                    }
                }
            }

            if ($data->count() > 0) {
                // Return success response with data
                return $this->setResponse(parent::SUCCESS, $data);
            } elseif ($this->builder->count() === 0) {
                // Return no data response
                return $this->setResponse(parent::NO_DATA, null, ['NO_DATA']);
            } else {
                // Throw an exception for unknown error
                throw new Exception('SOMETHING_WENT_WRONG');
            }
        } catch (\Exception $e) {
            // Return failed response with error message
            return $this->setResponse(parent::FAILED, null, [$e->getMessage()]);
        }
    }

    //    public function appCreateObject($object): object
    //    {
    //        // TODO: Implement appCreateObject() method.
    //    }
    //
    //    public function appById(string $id): object
    //    {
    //        // TODO: Implement appById() method.
    //    }
    //
    //    public function appUpdateObject($object, $id): object
    //    {
    //        // TODO: Implement appUpdateObject() method.
    //    }
    //
    //    public function appDeleteObject(string $id): object
    //    {
    //        // TODO: Implement appDeleteObject() method.
    //    }

    /**
     * Apply the time filter to a query builder instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $timeFilter
     * @param \Carbon\Carbon|null $customStartDate
     * @param \Carbon\Carbon|null $customEndDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyTimeFilter($query, $timeFilter = 'all', $customStartDate = null, $customEndDate = null)
    {
        switch ($timeFilter) {
            case 'week':
                $startDate = now()->subWeek()->startOfDay();
                $endDate = now();
                break;
            case 'month':
                $startDate = now()->subMonth()->startOfDay();
                $endDate = now();
                break;
            case 'custom':
                if ($customStartDate && $customEndDate) {
                    $startDate = $customStartDate->startOfDay();
                    $endDate = $customEndDate->endOfDay();
                } else {
                    $startDate = null;
                    $endDate = null;
                }
                break;
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    public function appGetGeneralCounts(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object
    {
        try {
            // Define the start and end dates based on the time filter
            $startDate = '2000-01-01';
            $endDate = today()->endOfDay();
            switch ($timeFilter) {
                case 'week':
                    $startDate = now()->subWeek()->startOfDay();
                    break;
                case 'month':
                    $startDate = now()->subMonth()->startOfDay();
                    break;
                //                case 'all':
                //                    $startDate = '2000-01-01';
                //                    $endDate = today()->endOfDay();
                //                    break;
                case 'custom':
                    if ($customStartDate && $customEndDate) {
                        $startDate = Carbon::parse($customStartDate)->startOfDay();
                        $endDate = Carbon::parse($customEndDate)->endOfDay();
                    }
                    break;
                //                default:
                //                    $startDate = '2000-01-01';
                //                    $endDate = today()->endOfDay();
                //                    break;
            }

                        $approvedUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $pendingUsersCount = User::where('is_approved', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $maleUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('gender', ['male', 'ذكر'])
                ->count();

            $femaleUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('gender', ['female', 'أنثى'])
                ->count();

            $activeUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['active', 'فعال', '1'])
                ->count();

            $inactiveUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['passive', 'غير فعال', '0'])
                ->count();

            $sickUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('is_has_disease', ['yes', 'نعم', '1'])
                ->count();

            $unsickUsersCount = User::where('is_approved', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('is_has_disease', ['no', 'لا', '0'])
                ->count();

            //Students Statistics

            $studentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1);
            })->count();

            $activeStudentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('status', '1');
            })->count();

            $inactiveStudentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('status', '0');
            })->count();

            $orphanStudentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('is_orphan', '1');
            })->count();

            $unorphanStudentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('is_orphan', '0');
            })->count();

            //Teachers Statistics
            $teachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1);
            })->count();

            $activeTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('status', '1');
            })->count();

            $inactiveTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('status', '0');
            })->count();

            $pendingTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 0);
            })->count();

            $marriedTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'married');
            })->count();

            $singleTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'single');
            })->count();


            $widowTeachersCount = Teacher::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'widow');
            })->count();


            //Admins Statistics
            $adminsCount = Admin::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1);
            })->count();

            $marriedAdminsCount = Admin::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'married');
            })->count();

            $singleAdminsCount = Admin::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'single');
            })->count();

            $widowAdminsCount = Admin::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1)
                    ->where('marital_status', 'widow');
            })->count();
//            dd($adminsCount,$marriedAdminsCount,$singleAdminsCount,$widowAdminsCount);

            $branchesCount = Branch::whereBetween('created_at', [$startDate, $endDate])->count();
            $propertiesCount = Property::whereBetween('created_at', [$startDate, $endDate])->count();

            $classRoomsCount = ClassRoom::whereBetween('created_at', [$startDate, $endDate])
                ->where('is_approved', 1)->count();

            $pendingClassRoomsCount = ClassRoom::whereBetween('created_at', [$startDate, $endDate])
                ->where('is_approved', 0)->count();

            //properties Statistics

            $mosqueCount = Property::where('property_type', 'mosque')->count();
            $schoolCount = Property::where('property_type', 'school')->count();



            $schoolClassroomCount = ClassRoom::whereHas('grade.property', function ($query) {
                $query->where('property_type', 'school');
            })->count();

            $mosqueClassroomCount = ClassRoom::whereHas('grade.property', function ($query) {
                $query->where('property_type', 'mosque');
            })->count();


            // users Statistics



            $sessionsCount = Session::whereBetween('date', [$startDate, $endDate])->count();
            $activitiesCount = Activity::whereBetween('created_at', [$startDate, $endDate])->count();

            $quizzesCount = Quiz::where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();
            $quranQuizzesCount = QuranQuiz::where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();
            $interviewsCount = Interview::where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->where('type', 'book')->count();
            $personalInterviewsCount = Interview::where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->where('type', '<>', 'book')->count();


//            dd(QuranQuiz::whereBetween('created_at', [$startDate, $endDate])
//                ->selectRaw('SUM(LENGTH(page) - LENGTH(REPLACE(page, ",", "")) + 1) as page_count')
//                ->value('page_count'));

            $faceToFaceQuizCount = QuranQuiz::whereBetween('date', [$startDate, $endDate])
                ->where('exam_type', 'recitationFromQuran')
//                ->get()
//                ->sum(function ($quiz) {
//                    return count(explode(',', $quiz->page));
//                })

                ->selectRaw('SUM(LENGTH(page) - LENGTH(REPLACE(page, ",", "")) + 1) as page_count')
                ->value('page_count');


            $absenceQuizCount = QuranQuiz::whereBetween('date', [$startDate, $endDate])
                ->where('exam_type', 'memorization')
//                ->get()
//                ->sum(function ($quiz) {
//                    return count(explode(',', $quiz->page));
//                });
                ->selectRaw('SUM(LENGTH(page) - LENGTH(REPLACE(page, ",", "")) + 1) as page_count')
                ->value('page_count');

            $writeArabicReadingQuizCount = QuranQuiz::whereBetween('date', [$startDate, $endDate])
                ->where('exam_type', 'correctArabicReading')
//                ->get()
//                ->sum(function ($quiz) {
//                    return count(explode(',', $quiz->page));
//                });
                ->selectRaw('SUM(LENGTH(page) - LENGTH(REPLACE(page, ",", "")) + 1) as page_count')
                ->value('page_count');
//            dd('t2');

            // Prepare the data to be returned
            $data = [
                [
                    'name' => 'All Approved Users',
                    'count' => $approvedUsersCount,
                ],
                [
                    'name' => 'All Pending Users',
                    'count' => $pendingUsersCount,
                ],
                [
                    'name' => 'All Male Users',
                    'count' => $maleUsersCount,
                ],
                [
                    'name' => 'All Female Users',
                    'count' => $femaleUsersCount,
                ],
                [
                    'name' => 'All Active Users',
                    'count' => $activeUsersCount,
                ],
                [
                    'name' => 'All Inactive Users',
                    'count' => $inactiveUsersCount,
                ],
                [
                    'name' => 'All Sick Users',
                    'count' => $sickUsersCount,
                ],
                [
                    'name' => 'All Healthy Users',
                    'count' => $unsickUsersCount,
                ],
                [
                    'name' => 'All Students',
                    'count' => $studentsCount,
                ],
                [
                    'name' => 'All Active Students',
                    'count' => $activeStudentsCount ,
                ],
                [
                    'name' => 'All Inactive Students',
                    'count' => $inactiveStudentsCount ,
                ],
                [
                    'name' => 'All Orphan Students',
                    'count' => $orphanStudentsCount,
                ],
                [
                    'name' => 'All Unorphan Students',
                    'count' => $unorphanStudentsCount,
                ],
                [
                    'name' => 'All Teachers',
                    'count' => $teachersCount,
                ],
                [
                    'name' => 'All Active Teachers',
                    'count' => $activeTeachersCount,
                ],
                [
                    'name' => 'All Inactive Teachers',
                    'count' => $inactiveTeachersCount,
                ],
                [
                    'name' => 'All Pending Teachers',
                    'count' => $pendingTeachersCount,
                ],

                [
                    'name' => 'All Married Teachers',
                    'count' => $marriedTeachersCount,
                ],
                [
                    'name' => 'All Single Teachers',
                    'count' => $singleTeachersCount,
                ],
                [
                    'name' => 'All Widow Teachers',
                    'count' => $widowTeachersCount,
                ],
                [
                    'name' => 'All Admins',
                    'count' => $adminsCount,
                ],
                [
                    'name' => 'All Married Admins',
                    'count' => $marriedAdminsCount,
                ],
                [
                    'name' => 'All Single Admins',
                    'count' => $singleAdminsCount,
                ],
                [
                    'name' => 'All Widow Admins',
                    'count' => $widowAdminsCount,
                ],
                [
                    'name' => 'All Branches',
                    'count' => $branchesCount,
                ],
                [
                    'name' => 'All Properties',
                    'count' => $propertiesCount,
                ],
                // Property Type Statistics (from optimized query)
                [
                    'name' => 'All Mosques',
                    'count' => $mosqueCount,
                ],
                [
                    'name' => 'All Schools',
                    'count' => $schoolCount,
                ],

                [
                    'name' => 'All Class Rooms',
                    'count' => $classRoomsCount,
                ],
                [
                    'name' => 'All Pending Class Rooms',
                    'count' => $pendingClassRoomsCount,
                ],
                [
                    'name' => 'School Classrooms',
                    'count' => $schoolClassroomCount,
                ],
                [
                    'name' => 'Mosque Classrooms',
                    'count' => $mosqueClassroomCount,
                ],
                [
                    'name' => 'All Sessions',
                    'count' => $sessionsCount,
                ],
                [
                    'name' => 'All Quizzes',
                    'count' => $quizzesCount,
                ],
                [
                    'name' => 'All Quran Quizzes',
                    'count' => $quranQuizzesCount,
                ],
                [
                    'name' => 'Books Interviews',
                    'count' => $interviewsCount,
                ],
                [
                    'name' => 'Activities',
                    'count' => $activitiesCount,
                ],
                [
                    'name' => 'Personal Interviews',
                    'count' => $personalInterviewsCount,
                ],
                [
                    'name' => 'Face-to-Face-Quiz',
                    'count' => (int)$faceToFaceQuizCount,
                ],
                [
                    'name' => 'Absence Quiz',
                    'count' => (int)$absenceQuizCount,
                ],
                [
                    'name' => 'Correct Arabic Reading Quiz',
                    'count' => (int)$writeArabicReadingQuizCount
                ],


            ];

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

    public function appGetTopLearners(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object
    {
        switch ($timeFilter) {
            case 'week':
                $startDate = now()->subWeek()->startOfDay();
                $endDate = now();
                break;
            case 'month':
                $startDate = now()->subMonth()->startOfDay();
                $endDate = now();
                break;
            case 'custom':
                if ($customStartDate && $customEndDate) {
                    $startDate = $customStartDate->startOfDay();
                    $endDate = $customEndDate->endOfDay();
                } else {
                    $startDate = '2000-01-01';
                    $endDate = now();
                }
                break;
            default:
                $startDate = '2000-01-01';
                $endDate = now();
                break;
        }

        $faceToFaceData = [];
        $absenceData = [];
        $writeArabicData = [];

        QuranQuiz::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('exam_type', ['memorization', 'recitationFromQuran', 'correctArabicReading'])
            ->with(['student.user'])
            ->chunk(10000, function ($quizzes) use (&$faceToFaceData, &$absenceData, &$writeArabicData) {
                foreach ($quizzes as $quiz) {
                    $student = $quiz->student;

                    $totalPages = collect(explode(',', $quiz->page))
                        ->filter(function ($page) {
                            return !empty($page);
                        })->unique()->count();

                    if ($student) {
                        $user = $student->user;

                        if ($user) {
                            $entry = [
                                'student_id' => $student->id,
                                'name' => $user->first_name . ' ' . $user->last_name,
                                'image' => $user->image,
                                'pages_count' => $totalPages,
                            ];

                            switch ($quiz->exam_type) {
                                case 'memorization':
                                    $absenceData[] = $entry;
                                    break;
                                case 'recitationFromQuran':
                                    $faceToFaceData[] = $entry;
                                    break;
                                case 'correctArabicReading':
                                    $writeArabicData[] = $entry;
                                    break;
                            }
                        }
                    }
                }
            });


        // Sort the results by page count in descending order for each array
        $absenceData = collect($absenceData)->sortByDesc('pages_count')->take(10)->values()->all();
        $faceToFaceData = collect($faceToFaceData)->sortByDesc('pages_count')->take(10)->values()->all();
        $writeArabicData = collect($writeArabicData)->sortByDesc('pages_count')->take(10)->values()->all();


        //      Return the response with the sorted data for each quiz type
        return $this->setResponse(
            parent::SUCCESS,
            [
                'face_to_face' => $faceToFaceData,
                'absence' => $absenceData,
                'write_arabic_reading' => $writeArabicData,
            ]
        );

    }

    private function getStudentExamPages($exams)
    {
        $pages = [];
        foreach ($exams as $exam) {
            $exam_pages = explode(',', $exam->page);
            $pages = array_merge($pages, $exam_pages);
        }
        //        return count(array_unique($pages));
        return count($pages);
    }


    public function classStatistics(string $id, string $startDate, string $endDate): object
    {
        try {
            $classroom = ClassRoom::with(['activities', 'sessions'])->findOrFail($id);

            if (!$classroom) {

                $data = [
                    [
                        'name' => 'Approved Students',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Pending Students',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Sessions',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Books',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Personal Interviews',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Face-to-Face Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Correct Arabic Reading Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Quizzes',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Interviews',
                        'count' => 0,
                    ],
                ];

                return $this->setResponse(
                    parent::SUCCESS,
                    $data,
                );
            }

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
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->count();

                    // Count interviews with type 'book' within the specified date range
                    $studentStatistics['interviews_count'] += $student->interviews()
                        ->where('type', 'book')
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->count();

                    // Count interviews with type not 'book' within the specified date range
                    $studentStatistics['personal_interviews_count'] += $student->interviews()
                        ->where('type', '<>', 'book')
                        ->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate)
                        ->count();
                }
            }

            $activities = $classroom->activities
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();
            $sessions = $classroom->sessions
                ->whereBetween('date', [$startDate, $endDate])
                ->count();


            $propertyType = $classroom->grade->property->property_type;

            $data = [
                [
                    'name' => 'Approved Students',
                    'count' => $approvedStudents ?? 0,
                ],
                [
                    'name' => 'Pending Students',
                    'count' => $unapprovedStudents ?? 0,
                ],
                [
                    'name' => 'Sessions',
                    'count' => $sessions ?? 0,
                ],
                [
                    'name' => 'Activities',
                    'count' => $activities ?? 0,
                ],
                [
                    'name' => 'Books',
                    'count' => $studentStatistics['interviews_count'] ?? 0,
                ],
                [
                    'name' => 'Personal Interviews',
                    'count' => $studentStatistics['personal_interviews_count'] ?? 0,
                ],
                [
                    'name' => 'Face-to-Face Quiz',
                    'count' => $studentStatistics['face_to_face_quiz_pages'] ?? 0,
                ],
                [
                    'name' => 'Absence Quiz',
                    'count' => $studentStatistics['absence_quiz_pages'] ?? 0,
                ],
                [
                    'name' => 'Correct Arabic Reading Quiz',
                    'count' => $studentStatistics['write_arabic_reading_quiz_pages'] ?? 0,
                ],
                [
                    'name' => 'Quizzes',
                    'count' => $studentStatistics['quizzes_count'] ?? 0,
                ],
                [
                    'name' => 'Interviews',
                    'count' => $studentStatistics['interviews_count'] ?? 0,
                ],
            ];
//            return $this->studentStatistics($id, $startDate, $endDate);

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


    public function appExportData(?string $start_date = null, ?string $end_date = null): object
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);

//        $classrooms = ClassRoom::with('grade.property.branch')->get();

        $classrooms = ClassRoom::with([
            'grade.property.propertyAdmins.admin.user',
            'grade.property.branch.branchAdmins.admin.user', // Load branch admins with their user data
            'classRoomTeachers.teacher.user' // Load classroom teachers with their user data
        ])->get();


        $start_date = $start_date === "null" ? null : $start_date;
        $end_date = $end_date === "null" ? null : $end_date;

        // Set default dates if not provided
        $startDate = $start_date ?? now()->subMonth()->startOfMonth()->format('d-m-Y');
        $endDate = $end_date ?? now()->subMonth()->endOfMonth()->format('d-m-Y');


        // Format the provided dates
        if ($start_date !== null) {
            $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
        }
        if ($end_date !== null) {
            $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $end_date)->format('Y-m-d');
        }


        $filename = now()->format('d-m-Y') . ' Branches Info from ' . $startDate . ' to ' . $endDate . '.csv';


        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        // Attribute translations in Arabic
        $attributeTranslations = [
            'branch' => 'المنطقة',
            'branch_admins' => 'مدراء المنطقة',
            'property' => 'المركز',
            'property_admins' => 'مدراء المركز',
            'property_type' => 'نوع المركز',
            'grade' => 'المستوى',
            'classroom' => 'الصف',
            'classroom_teachers' => 'معلم الصف',
            'description' => 'وصف الصف',
            'capacity' => 'سعة الصف',
        ];

        // Static columns for statistics
        $statisticColumns = [
            'Approved Students' => 'عدد الطلاب النشطين',
            'Pending Students' => 'عدد الطلاب المعلقين',
            'Sessions' => 'عدد الجلسات',
            'Quizzes' => 'عدد الاختبارات',
            'Activities' => 'عدد الأنشطة',
            'Books' => 'عدد الكتب',
            'Face-to-Face Quiz' => 'عدد الاختبارات وجهاً لوجه',
            'Absence Quiz' => 'عدد اختبارات الغياب',
            'Correct Arabic Reading Quiz' => 'عدد اختبارات القراءة الصحيحة',
            'Interviews' => 'عدد المقابلات',
        ];

        $callback = function () use ($classrooms, $attributeTranslations, $statisticColumns, $startDate, $endDate) {
            // Open file with UTF-8 encoding
            $file = fopen('php://output', 'w');

            // Set UTF-8 encoding for the output stream
            stream_filter_append($file, 'convert.iconv.UTF-8/UTF-8');

            // Write UTF-8 BOM (Byte Order Mark)
            fwrite($file, "\xEF\xBB\xBF");

            // Write headers in Arabic
            $columns = array_values($attributeTranslations);
            $columns = array_merge($columns, array_values($statisticColumns));
            fputcsv($file, $columns);

            // Helper function to get branch admins names
            $getBranchAdminsNames = function($branch) {
                if (!$branch || !$branch->branchAdmins) {
                    return 'لا يوجد';
                }

                $adminNames = [];
                foreach ($branch->branchAdmins as $branchAdmin) {
                    $adminName = $branchAdmin->admin?->user?->first_name . ' ' . $branchAdmin->admin?->user?->last_name;

                    if (trim($adminName)) {
                        $adminNames[] = trim($adminName);
                    }
                }

                return empty($adminNames) ? 'لا يوجد' : implode(', ', $adminNames);
            };


            // Helper function to get branch admins names
            $getPropertyAdminsNames = function($property) {
                if (!$property || !$property->propertyAdmins) {
                    return 'لا يوجد';
                }

                $adminNames = [];
                foreach ($property->propertyAdmins as $propertyAdmin) {
                    $adminName = $propertyAdmin->admin?->user?->first_name . ' ' . $propertyAdmin->admin?->user?->last_name;

                    if (trim($adminName)) {
                        $adminNames[] = trim($adminName);
                    }
                }

                return empty($adminNames) ? 'لا يوجد' : implode(', ', $adminNames);
            };

            // Helper function to get classroom teachers names
            $getClassroomTeachersNames = function($classroom) {
                if (!$classroom || !$classroom->classRoomTeachers) {
                    return 'لا يوجد';
                }

                $teacherNames = [];
                foreach ($classroom->classRoomTeachers as $classRoomTeacher) {
                    $teacherName = $classRoomTeacher->teacher?->user?->first_name . ' ' . $classRoomTeacher->teacher?->user?->last_name;

                    if (trim($teacherName)) {
                        $teacherNames[] = trim($teacherName);
                    }
                }

                return empty($teacherNames) ? 'لا يوجد' : implode(', ', $teacherNames);
            };

            foreach ($classrooms as $classroom) {
                // Prepare data row with Arabic-friendly formatting
                $row = [];
                foreach ($attributeTranslations as $attribute => $translation) {
                    $value = match ($attribute) {
                        'branch' => $classroom->grade->property->branch->id . ' - ' . $classroom->grade->property->branch->name,
                        'branch_admins' => $getBranchAdminsNames($classroom->grade->property->branch), // New field
                        'property' => $classroom->grade->property->id . ' - ' . $classroom->grade->property->name,
                        'property_admins' => $getPropertyAdminsNames($classroom->grade->property), // New field
                        'property_type' => match ($classroom->grade->property->property_type) {
                            'mosque' => __('مسجد'),
                            'school' => __('مدرسة'),
                            default => $classroom->grade->property->property_type,
                        },
                        'grade' => $classroom->grade->id . ' - ' . $classroom->grade->name,
                        'classroom' => $classroom->id . ' - ' . $classroom->name,
                        'classroom_teachers' => $getClassroomTeachersNames($classroom), // New field
                        'description' => $classroom->description,
                        'capacity' => $classroom->capacity,

                        default => $classroom->$attribute,
                    };
                    if (is_string($value)) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                    $row[] = $value;
                }

                // Get classroom statistics data

                $statisticsResponse = $this->classStatistics($classroom->id, $startDate, $endDate);
                $statisticsData = $statisticsResponse?->data ?? [];


                // Add classroom statistics to the row
                foreach ($statisticColumns as $columnName => $translation) {
                    $value = 0;
                    foreach ($statisticsData as $statistic) {
                        if ($statistic['name'] === $columnName) {
                            $value = $statistic['count'];
                            break;
                        }
                    }
                    $row[] = $value;
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        // Create a custom response object that can be processed by setResponse()
        $response = new \stdClass();
        $response->callback = $callback;
        $response->headers = $headers;

        return $this->setResponse('success', $response);
    }


    private function studentStatistics(string $id, ?string $startDate = null, ?string $endDate = null): object
    {
        try {
            Log::debug('Fetching student with ID: ' . $id);
            $student = Student::withCount(['quizzes', 'interviews', 'notes', 'sessionAttendances', 'activityParticipants'])->findOrFail($id);
            Log::debug('Student fetched successfully: ' . $student->id);

            // Get the student's latest active classroom enrollment
            $latestEnrollment = ClassRoomStudent::where('student_id', $student->id)
                ->whereNull('left_at')
                ->orderByDesc('joined_at')
                ->first();

            if (!$latestEnrollment) {
                // Return 0 values for all statistics when the student doesn't have an active enrollment
                $data = [
                    [
                        'name' => 'Sessions',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Unattended Sessions',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Activities',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Unattended Activities',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Books',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Personal Interviews',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Face-to-Face Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Absence Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Correct Arabic Reading Quiz',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Quizzes',
                        'count' => 0,
                    ],
                    [
                        'name' => 'Notes',
                        'count' => 0,
                    ]
                ];

                return $this->setResponse(
                    parent::SUCCESS,
                    $data,
                );
            }

            $classRoomId = $latestEnrollment->class_room_id;
            $enrollmentDate = $latestEnrollment->joined_at;

            // Set default dates if not provided
            $startDate = $startDate ?? $enrollmentDate;
            $endDate = $endDate ?? now();

            // Get all sessions belonging to the class room after the enrollment date and within the specified date range
            $totalSessions = Session::where('class_room_id', $classRoomId)
                ->where('date', '>=', $enrollmentDate)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();


            $attendedSessionCount = $student->sessionAttendances()
                ->whereHas('session', function ($query) use ($classRoomId, $enrollmentDate, $startDate, $endDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('date', '>=', $enrollmentDate)
                        ->whereBetween('date', [$startDate, $endDate]);
                })->count();
            $unattended_session_count = max(0, $totalSessions - $attendedSessionCount); // Ensure non-negative count
            // Get all activities belonging to the class room after the enrollment date and within the specified date range
            $totalActivities = Activity::where('class_room_id', $classRoomId)
                ->where('start_datetime', '>=', $enrollmentDate)
                ->where('start_datetime', '>=', $startDate)
                ->where('start_datetime', '<=', $endDate)
                ->count();
            $attendedActivityCount = $student->activityParticipants()
                ->whereHas('activity', function ($query) use ($classRoomId, $enrollmentDate, $startDate, $endDate) {
                    $query->where('class_room_id', $classRoomId)
                        ->where('created_at', '>=', $enrollmentDate)
                        ->where('start_datetime', '>=', $startDate)
                        ->where('start_datetime', '<=', $endDate);
                })
                ->count();
            $unattended_activity_count = max(0, $totalActivities - $attendedActivityCount); // Ensure non-negative count

            $face_to_face_quiz = $student->quranQuizzes()
                ->where('exam_type', 'recitationFromQuran')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            $quran_quiz_pages = $this->getStudentExamPages($face_to_face_quiz);

            $absence_quiz = $student->quranQuizzes()
                ->where('exam_type', 'memorization')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            $absence_quiz_pages = $this->getStudentExamPages($absence_quiz);

            $write_arabic_reading_quiz = $student->quranQuizzes()
                ->where('exam_type', 'correctArabicReading')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            $write_arabic_reading_quiz_pages = $this->getStudentExamPages($write_arabic_reading_quiz);

            // Retrieve interviews where type is 'book' and within the specified date range
            $book_interview_count = $student->interviews()
                ->where('type', 'book')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();
            // Retrieve interviews where type is not 'book' and within the specified date range
            $personal_interview_count = $student->interviews()
                ->where('type', '<>', 'book')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();

            // Retrieve notes where type is not 'book' and within the specified date range
            $note_count = $student->notes()
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();

            // Retrieve quizzes where type is not 'book' and within the specified date range
            $quiz_count = $student->quizzes()
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->count();


            $data = [
                [
                    'name' => 'Sessions',
                    'count' => $attendedSessionCount,
                ],
                [
                    'name' => 'Unattended Sessions',
                    'count' => $unattended_session_count,
                ],
                [
                    'name' => 'Activities',
                    'count' => $attendedActivityCount,
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
                    'count' => $quiz_count,
                ],
                [
                    'name' => 'Notes',
                    'count' => $note_count,
                ]
            ];


            Log::debug('Statistics data generated successfully for student: ' . $student->id);

            return $this->setResponse(
                parent::SUCCESS,
                $data,
            );
        } catch (\Exception $e) {
            Log::error('Error in studentStatistics function for student ' . $id . ': ' . $e->getMessage());
            Log::error($e->getTraceAsString());


            return $this->setResponse(
                parent::NO_DATA,
                null,
                [$e->getMessage()]
            );
        }
    }


    public function appPropertyExportData(string $propertyId, ?string $start_date = null, ?string $end_date = null): object
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);

        //$property = Property::with('branch', 'grades.classRooms.classRoomStudents.student')->findOrFail($propertyId);

        // Updated eager loading to include branch admins and classroom teachers
        $property = Property::with([
            'propertyAdmins.admin.user',
            'branch.branchAdmins.admin.user',
            'grades.classRooms.classRoomStudents' => function ($query) {
                // This condition filters the 'class_room_students' pivot records.
                $query->whereNull('left_at');
            },
            'grades.classRooms.classRoomStudents.student',
            'grades.classRooms.classRoomTeachers.teacher.user' // Assuming ClassRoomTeacher has teacher relation which has user
        ])->findOrFail($propertyId);

        $start_date = $start_date === "null" ? null : $start_date;
        $end_date = $end_date === "null" ? null : $end_date;

        // Set default dates if not provided
        $startDate = $start_date ?? now()->subMonth()->startOfMonth()->format('d-m-Y');
        $endDate = $end_date ?? now()->subMonth()->endOfMonth()->format('d-m-Y');


        // Format the provided dates
        if ($start_date !== null) {
            $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
        }
        if ($end_date !== null) {
            $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $end_date)->format('Y-m-d');
        }

        $filename = now()->format('d-m-Y') . ' Property ' . $property->name . ' Info from ' . $startDate . ' to ' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        // Attribute translations in Arabic
        $attributeTranslations = [
            'branch' => 'المنطقة',
            'branch_admins' => 'مدراء المنطقة',
            'property' => 'المركز',
            'property_admins' => 'مدراء المركز',
            'property_type' => 'نوع المركز',
            'grade' => 'المستوى',
            'classroom' => 'الصف',
            'classroom_teachers' => 'معلم الصف',
            'student_id' => 'المعرف',
            'student_email' => 'البريد الإلكتروني',
            'student_first_name' => 'الاسم الأول',
            'student_last_name' => 'اسم العائلة',
            'student_username' => 'اسم المستخدم',
            'student_enrollment_date' => 'تاريخ التسجيل في الصف',
            'student_identity_number' => 'رقم الهوية',
            'student_phone' => 'رقم الهاتف',
            'student_gender' => 'الجنس',
            'student_birth_date' => 'تاريخ الميلاد',
            'student_birth_place' => 'مكان الميلاد',
            'student_father_name' => 'اسم الأب',
            'student_mother_name' => 'اسم الأم',
            'student_blood_type' => 'فصيلة الدم',
            'student_note' => 'ملاحظة',
            'student_current_address' => 'العنوان الحالي',
            'student_is_has_disease' => 'هل لديه مرض',
            'student_disease_name' => 'اسم المرض',
            'student_is_has_treatment' => 'هل يتلقى علاجًا',
            'student_treatment_name' => 'اسم العلاج',
            'student_are_there_disease_in_family' => 'هل هناك أمراض في العائلة',
            'student_family_disease_note' => 'ملاحظة أمراض العائلة',
            'student_status' => 'الحالة',
        ];

        // Static columns for statistics
        $statisticColumns = [
            'Activities' => 'عدد الأنشطةالمحضورة',
            'Unattended Activities' => 'عدد الأنشطة الغير محضورة',
            'Sessions' => 'عدد الجلسات المحضورة',
            'Unattended Sessions' => 'عدد الجلسات الغير محضورة',
            'Books' => 'عدد الكتب المقروءة للطالب',
            'Personal Interviews' => 'عدد المقابلات الشخصية للطالب',
            'Face-to-Face Quiz' => 'عدد الاختبارات وجهاً لوجه للطالب',
            'Absence Quiz' => 'عدد اختبارات غيبا للطالب',
            'Correct Arabic Reading Quiz' => 'عدد اختبارات القراءة الصحيحة للطالب',
            'Quizzes' => 'عدد اختبارات الطالب',
            'Notes' => 'عدد الملاحظات'
        ];

        $callback = function () use ($property, $attributeTranslations, $statisticColumns, $startDate, $endDate) {
            // Open file with UTF-8 encoding
            $file = fopen('php://output', 'w');

            // Set UTF-8 encoding for the output stream
            stream_filter_append($file, 'convert.iconv.UTF-8/UTF-8');

            // Write UTF-8 BOM (Byte Order Mark)
            fwrite($file, "\xEF\xBB\xBF");

            // Write headers in Arabic
            $columns = array_values($attributeTranslations);
            $columns = array_merge($columns, array_values($statisticColumns));
            fputcsv($file, $columns);

            // Helper function to get branch admins names
            $getBranchAdminsNames = function($branch) {
                if (!$branch || !$branch->branchAdmins) {
                    return 'لا يوجد';
                }

                $adminNames = [];
                foreach ($branch->branchAdmins as $branchAdmin) {
                    // Adjust the relation path based on your actual model structure
                    $adminName = $branchAdmin->admin?->user?->first_name . ' ' . $branchAdmin->admin?->user?->last_name;

                    if (trim($adminName)) {
                        $adminNames[] = trim($adminName);
                    }
                }

                return empty($adminNames) ? 'لا يوجد' : implode(', ', $adminNames);
            };

            // Helper function to get branch admins names
            $getPropertyAdminsNames = function($property) {
                if (!$property || !$property->propertyAdmins) {
                    return 'لا يوجد';
                }

                $adminNames = [];
                foreach ($property->propertyAdmins as $propertyAdmin) {
                    $adminName = $propertyAdmin->admin?->user?->first_name . ' ' . $propertyAdmin->admin?->user?->last_name;

                    if (trim($adminName)) {
                        $adminNames[] = trim($adminName);
                    }
                }

                return empty($adminNames) ? 'لا يوجد' : implode(', ', $adminNames);
            };

            // Helper function to get classroom teachers names
            $getClassroomTeachersNames = function($classroom) {
                if (!$classroom || !$classroom->classRoomTeachers) {
                    return 'لا يوجد';
                }

                $teacherNames = [];
                foreach ($classroom->classRoomTeachers as $classRoomTeacher) {
                    // Adjust the relation path based on your actual model structure
                    $teacherName = $classRoomTeacher->teacher?->user?->first_name . ' ' . $classRoomTeacher->teacher?->user?->last_name;

                    if (trim($teacherName)) {
                        $teacherNames[] = trim($teacherName);
                    }
                }

                return empty($teacherNames) ? 'لا يوجد' : implode(', ', $teacherNames);
            };

            foreach ($property->grades as $grade) {
                foreach ($grade->classRooms as $classroom) {
                    foreach ($classroom->classRoomStudents as $classRoomStudent) {
                        $student = $classRoomStudent->student;

                        // Prepare data row
                        $row = [];
                        foreach ($attributeTranslations as $attribute => $translation) {
                            $value = match ($attribute) {
                                'branch' => $property->branch->id . ' - ' . $property->branch->name,
                                'branch_admins' => $getBranchAdminsNames($property->branch),
                                'property' => $property->id . ' - ' . $property->name,
                                'property_admins' => $getPropertyAdminsNames($property), // New field
                                'property_type' => match ($property->property_type) {
                                    'mosque' => __('مسجد'),  // or trans('mosque')
                                    'school' => __('مدرسة'),  // or trans('school')
                                    default => $property->property_type,
                                },
                                'grade' => $grade->id . ' - ' . $grade->name,
                                'classroom' => $classroom->id . ' - ' . $classroom->name,
                                'classroom_teachers' => $getClassroomTeachersNames($classroom),
                                'student_id' => $student->id,
                                'student_email' => $student->user?->email,
                                'student_first_name' => $student->user?->first_name,
                                'student_last_name' => $student->user?->last_name,
                                'student_username' => $student->user?->username,
                                'student_identity_number' => $student->user?->identity_number,
                                'student_phone' => $student->user?->phone,
                                'student_gender' => match ($student->user?->gender) {
                                    'male' => 'ذكر',
                                    'female' => 'أنثى',
                                    default => $student->user?->gender,
                                },
                                'student_birth_date' => $student->user?->birth_date,
                                'student_birth_place' => $student->user?->birth_place,
                                'student_father_name' => $student->user?->father_name,
                                'student_mother_name' => $student->user?->mother_name,
                                'student_blood_type' => $student->user?->blood_type,
                                'student_note' => $student->user?->note,
                                'student_current_address' => $student->user?->current_address,
                                'student_is_has_disease' => $student->user?->is_has_disease ? 'نعم' : 'لا',
                                'student_disease_name' => $student->user?->disease_name,
                                'student_is_has_treatment' => $student->user?->is_has_treatment ? 'نعم' : 'لا',
                                'student_treatment_name' => $student->user?->treatment_name,
                                'student_are_there_disease_in_family' => $student->user?->are_there_disease_in_family ? 'نعم' : 'لا',
                                'student_family_disease_note' => $student->user?->family_disease_note,
                                'student_status' => $student->user?->status ? 'نشط' : 'غير نشط',
                                'student_enrollment_date' => $classRoomStudent->joined_at,
                            };

                            if (is_string($value)) {
                                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                            }
                            $row[] = $value;
                        }

                        // Get student statistics data
                        $statisticsResponse = $this->studentStatistics($student->id, $startDate, $endDate);
//                        Log::debug('Statistics Response for student ' . $student->id . ': ' . json_encode($statisticsResponse));
                        $statisticsData = $statisticsResponse?->data ?? []; // Use an empty array if data is null


                        // Add student statistics to the row
                        foreach ($statisticColumns as $columnName => $translation) {
                            $value = 0;
                            foreach ($statisticsData as $statistic) {
                                if ($statistic['name'] === $columnName) {
                                    $value = $statistic['count'];
                                    break;
                                }
                            }
                            $row[] = $value;
                        }

                        fputcsv($file, $row);
                    }
                }
            }

            fclose($file);
        };

        // Create a custom response object that can be processed by setResponse()
        $response = new \stdClass();
        $response->callback = $callback;
        $response->headers = $headers;

        return $this->setResponse('success', $response);
    }



//    public function appExportData(string $dataModel, int $id): object
//    {
//        $users = User::get();
//
//        $filename = 'users_' . date('YmdHis') . '.csv';
//        $headers = [
//            'Content-Type' => 'text/csv; charset=utf-8',
//            'Content-Disposition' => 'attachment; filename=' . $filename,
//        ];
//
//        // Attribute translations in Arabic
//        $attributeTranslations = [
//            'id' => 'المعرف',
//            'property_id' => 'معرف العقار',
//            'email' => 'البريد الإلكتروني',
//            'first_name' => 'الاسم الأول',
//            'last_name' => 'اسم العائلة',
//            'username' => 'اسم المستخدم',
//            'identity_number' => 'رقم الهوية',
//            'phone' => 'رقم الهاتف',
//            'gender' => 'الجنس',
//            'birth_date' => 'تاريخ الميلاد',
//            'birth_place' => 'مكان الميلاد',
//            'father_name' => 'اسم الأب',
//            'mother_name' => 'اسم الأم',
//            'qr_code' => 'رمز الاستجابة السريعة',
//            'blood_type' => 'فصيلة الدم',
//            'note' => 'ملاحظة',
//            'current_address' => 'العنوان الحالي',
//            'is_has_disease' => 'هل لديه مرض',
//            'disease_name' => 'اسم المرض',
//            'is_has_treatment' => 'هل يتلقى علاجًا',
//            'treatment_name' => 'اسم العلاج',
//            'are_there_disease_in_family' => 'هل هناك أمراض في العائلة',
//            'family_disease_note' => 'ملاحظة أمراض العائلة',
//            'status' => 'الحالة',
//            'image' => 'الصورة',
//            'is_approved' => 'تمت الموافقة'
//        ];
//
//        // Attributes to exclude from the export
//        $excludedAttributes = ['qr_code', 'image'];
//
//        // Static columns for statistics
//        $statisticColumns = [
//            'Activities' => 'عدد الأنشطة',
//            'Unattended Activities' => 'عدد الأنشطة الغير محضورة',
//            'Sessions' => 'عدد الجلسات',
//            'Unattended Sessions' => 'عدد الجلسات الغير محضورة',
//            'Books' => 'عدد الكتب',
//            'Personal Interviews' => 'عدد المقابلات الشخصية',
//            'Absence Quiz' => 'عدد اختبارات الغياب',
//            'Face-to-Face Quiz' => 'عدد الاختبارات وجهاً لوجه',
//            'Correct Arabic Reading Quiz' => 'عدد اختبارات القراءة الصحيحة',
//            'Quizzes' => 'عدد الاختبارات',
//            'Notes' => 'عدد الملاحظات'
//        ];
//
//        $callback = function () use ($users, $attributeTranslations, $excludedAttributes, $statisticColumns) {
//            // Open file with UTF-8 encoding
//            $file = fopen('php://output', 'w');
//
//            // Set UTF-8 encoding for the output stream
//            stream_filter_append($file, 'convert.iconv.UTF-8/UTF-8');
//
//            // Write UTF-8 BOM (Byte Order Mark)
//            fwrite($file, "\xEF\xBB\xBF");
//
//            // Write headers in Arabic
//            $columns = array_values(array_diff($attributeTranslations, $excludedAttributes));
//            $columns = array_merge($columns, array_values($statisticColumns));
//            fputcsv($file, $columns);
//
//            foreach ($users as $user) {
//                // Prepare data row with Arabic-friendly formatting
//                $row = [];
//                foreach ($attributeTranslations as $attribute => $translation) {
//                    if (!in_array($attribute, $excludedAttributes)) {
//                        $value = $user->$attribute;
//                        if (is_string($value)) {
//                            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
//                        }
//                        $row[] = $value;
//                    }
//                }
//
//                // Get student statistics data
//                $statisticsData = $this->studentStatistics($user->id);
//
//                // Add student statistics to the row
//                foreach ($statisticColumns as $columnName => $translation) {
//                    $value = 0;
//                    if (isset($statisticsData->$columnName)) {
//                        $value = $statisticsData->$columnName;
//                    }
//                    $row[] = $value;
//                }
//
//                fputcsv($file, $row);
//            }
//
//            fclose($file);
//        };
//
//        // Create a custom response object that can be processed by setResponse()
//        $response = new \stdClass();
//        $response->callback = $callback;
//        $response->headers = $headers;
//
//        return $this->setResponse('success', $response);
//    }

//    public function appExportData(string $dataModel, int $id): object
//    {
//
//        $users = User::get();
//
//        $filename = 'users_' . date('YmdHis') . '.csv';
//        $headers = [
//            'Content-Type' => 'text/csv; charset=utf-8',
//            'Content-Disposition' => 'attachment; filename=' . $filename,
//        ];
//
////        // Translated column names in Arabic
////        $columns = [
////            'المعرف',            // ID
////            'الاسم الأول',     // First Name
////            'اسم العائلة',     // Last Name
////            'اسم المستخدم',    // Username
////            'البريد الإلكتروني', // Email
////            'تاريخ الإنشاء',   // Created At
////            'تاريخ التحديث'    // Updated At
////        ];
//
//
//        // Attribute translations in Arabic
//        $attributeTranslations = [
//            'id' => 'المعرف',
//            'property_id' => 'معرف العقار',
//            'email' => 'البريد الإلكتروني',
//            'first_name' => 'الاسم الأول',
//            'last_name' => 'اسم العائلة',
//            'username' => 'اسم المستخدم',
//            'identity_number' => 'رقم الهوية',
//            'phone' => 'رقم الهاتف',
//            'gender' => 'الجنس',
//            'birth_date' => 'تاريخ الميلاد',
//            'birth_place' => 'مكان الميلاد',
//            'father_name' => 'اسم الأب',
//            'mother_name' => 'اسم الأم',
//            'qr_code' => 'رمز الاستجابة السريعة',
//            'blood_type' => 'فصيلة الدم',
//            'note' => 'ملاحظة',
//            'current_address' => 'العنوان الحالي',
//            'is_has_disease' => 'هل لديه مرض',
//            'disease_name' => 'اسم المرض',
//            'is_has_treatment' => 'هل يتلقى علاجًا',
//            'treatment_name' => 'اسم العلاج',
//            'are_there_disease_in_family' => 'هل هناك أمراض في العائلة',
//            'family_disease_note' => 'ملاحظة أمراض العائلة',
//            'status' => 'الحالة',
//            'image' => 'الصورة',
//            'is_approved' => 'تمت الموافقة'
//        ];
//
//        // Attributes to exclude from the export
//        $excludedAttributes = ['qr_code', 'image'];
//
//        // Static columns for statistics
//        $statisticColumns = [
//            'عدد الحضور' => 'attendance_count',
//            'عدد أيام الغياب' => 'absence_days',
//            'متوسط الدرجات' => 'average_grade'
//        ];
//
//        $callback = function () use ($users, $attributeTranslations, $excludedAttributes, $statisticColumns) {
//            // Open file with UTF-8 encoding
//            $file = fopen('php://output', 'w');
//
//            // Set UTF-8 encoding for the output stream
//            stream_filter_append($file, 'convert.iconv.UTF-8/UTF-8');
//
//            // Write UTF-8 BOM (Byte Order Mark)
//            fwrite($file, "\xEF\xBB\xBF");
//
//            // Write headers in Arabic
//            $columns = array_values(array_diff($attributeTranslations, $excludedAttributes));
//            $columns = array_merge($columns, array_values($statisticColumns));
//            fputcsv($file, $columns);
//
//            foreach ($users as $user) {
//                // Prepare data row with Arabic-friendly formatting
//                $row = [];
//                foreach ($attributeTranslations as $attribute => $translation) {
//                    if (!in_array($attribute, $excludedAttributes)) {
//                        $value = $user->$attribute;
//                        if (is_string($value)) {
//                            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
//                        }
//                        $row[] = $value;
//                    }
//                }
//
//                // Add student-specific statistics
//                $row[] = $user->attendances()->count();
//                $row[] = $user->absences()->count();
//                $row[] = $user->grades()->avg('grade');
//
//                fputcsv($file, $row);
//            }
//
//            fclose($file);
//        };
//
//        // Create a custom response object that can be processed by setResponse()
//        $response = new \stdClass();
//        $response->callback = $callback;
//        $response->headers = $headers;
//
//        return $this->setResponse('success', $response);
//    }

    // Retrieve the data based on the selected dataModel and ID
//        switch ($dataModel) {
//            case 'branch':
//                $data = $this->analyticsRepository->getDataByBranch($id, $startDate, $endDate, $attributes);
//                break;
//            case 'property':
//                $data = $this->analyticsRepository->getDataByProperty($id, $startDate, $endDate, $attributes);
//                break;
//            case 'grade':
//                $data = $this->analyticsRepository->getDataByGrade($id, $startDate, $endDate, $attributes);
//                break;
//            case 'classroom':
//                $data = $this->analyticsRepository->getDataByClassroom($id, $startDate, $endDate, $attributes);
//                break;
//            default:
//                // Handle invalid dataModel
//                return response()->json(['error' => 'Invalid data model'], 400);
//        }


}
