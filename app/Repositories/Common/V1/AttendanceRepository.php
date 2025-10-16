<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IAttendance;
use App\Models\Morphs\Attendance;
use App\Repositories\BaseRepository;

class AttendanceRepository extends BaseRepository implements IAttendance
{
    protected $model = Attendance::class;

    public function __construct()
    {
        $this->build();
    }
}
