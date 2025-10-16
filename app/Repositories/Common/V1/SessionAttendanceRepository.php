<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ISessionAttendance;
use App\Models\SessionAttendance;
use App\Repositories\BaseRepository;

class SessionAttendanceRepository extends BaseRepository implements ISessionAttendance
{
    protected $model = SessionAttendance::class;

    public function __construct()
    {
        $this->build();
    }
}
