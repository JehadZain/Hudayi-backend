<?php

namespace App\Repositories\Mobile\V1;

use App\Interfaces\Mobile\V1\IMobileAnalytics;
use App\Models\Branch;
use App\Models\Properties\ClassRoom;
use App\Models\Properties\Property;
use App\Models\Users\Admin;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Models\Users\User;
use App\Repositories\Common\V1\AnalyticsRepository;
use Carbon\Carbon;
use Exception;

class MobileAnalyticsRepository extends AnalyticsRepository implements IMobileAnalytics
{
    /**
     * Retrieve property data with various counts and filtering.
     *
     * @param  null  $params Additional parameters (optional).
     * @return object Response object with property data and appropriate status.
     *
     * @throws Exception If something goes wrong during the process.
     */
    public function mobileAll($params = null): object
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
//                $property->unapproved_students_count = $property->grades->sum(function ($grade) {
//                    return $grade->classRooms->sum(function ($classRoom) {
//                        return $classRoom->classRoomStudents()
//                            ->whereHas('student.user', function ($query) {
//                                $query->where('is_approved', 0);
//                            })
//                            ->count();
//                    });
//                });

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

    //    public function mobileCreateObject($object): object
    //    {
    //        // TODO: Implement appCreateObject() method.
    //    }
    //
    //    public function mobileById(string $id): object
    //    {
    //        // TODO: Implement appById() method.
    //    }
    //
    //    public function mobileUpdateObject($object, $id): object
    //    {
    //        // TODO: Implement appUpdateObject() method.
    //    }
    //
    //    public function mobileDeleteObject(string $id): object
    //    {
    //        // TODO: Implement appDeleteObject() method.
    //    }

    /**
     * Apply the time filter to a query builder instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $timeFilter
     * @param  \Carbon\Carbon|null  $customStartDate
     * @param  \Carbon\Carbon|null  $customEndDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function mobilelyTimeFilter($query, $timeFilter = 'all', $customStartDate = null, $customEndDate = null)
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

    public function mobileGetGeneralCounts(string $timeFilter = 'all', Carbon $customStartDate = null, Carbon $customEndDate = null): object
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

            //Users Statistics
            $approvedUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])->count();
            $pendingUsersCount = User::where('is_approved', 0)->whereBetween('created_at', [$startDate, $endDate])->count();
            $maleUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('gender', ['male', 'ذكر'])
                ->count();

            $femaleUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('gender', ['female', 'أنثى'])
                ->count();

            $activeUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['active', 'فعال', '1'])
                ->count();

            $inactiveUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['passive', 'غير فعال', '0'])
                ->count();

            $sickUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('is_has_disease', ['yes', 'نعم', '1'])
                ->count();

            $unsickUsersCount = User::where('is_approved', 1)->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('is_has_disease', ['no', 'لا', '0'])
                ->count();

            //Students Statistics

            $studentsCount = Student::whereHas('user', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_approved', 1);
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

            $branchesCount = Branch::whereBetween('created_at', [$startDate, $endDate])->count();
            $propertiesCount = Property::whereBetween('created_at', [$startDate, $endDate])->count();

            $classRoomsCount = ClassRoom::whereBetween('created_at', [$startDate, $endDate])
                ->where('is_approved', 1)->count();

            $pendingClassRoomsCount = ClassRoom::whereBetween('created_at', [$startDate, $endDate])
                ->where('is_approved', 0)->count();

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
                    'name' => 'All Pending Teachers',
                    'count' => $pendingTeachersCount,
                ],
                [
                    'name' => 'All Married Teachers',
                    'count' => $marriedTeachersCount,
                ],
                [
                    'name' => 'All Unmarried Teachers',
                    'count' => $singleTeachersCount,
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
                    'name' => 'All Unmarried Admins',
                    'count' => $singleAdminsCount,
                ],
                [
                    'name' => 'All Branches',
                    'count' => $branchesCount,
                ],
                [
                    'name' => 'All Properties',
                    'count' => $propertiesCount,
                ],
                [
                    'name' => 'All Class Rooms',
                    'count' => $classRoomsCount,
                ],
                [
                    'name' => 'All Pending Class Rooms',
                    'count' => $pendingClassRoomsCount,
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
}
