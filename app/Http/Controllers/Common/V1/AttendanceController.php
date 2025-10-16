<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppAttendance;
use App\Interfaces\Common\V1\IAttendance;
use App\Interfaces\Mobile\V1\IMobileAttendance;
use App\Models\Morphs\Attendance;

//model

class AttendanceController extends Controller
{
    protected $model = Attendance::class;

    public function __construct(IAttendance $model, IAppAttendance $appModel, IMobileAttendance $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
