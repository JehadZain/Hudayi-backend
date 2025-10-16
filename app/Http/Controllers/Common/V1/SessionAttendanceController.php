<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppSessionAttendance;
//model
use App\Interfaces\Common\V1\ISessionAttendance;
use App\Interfaces\Mobile\V1\IMobileSessionAttendance;
use App\Models\SessionAttendance;

class SessionAttendanceController extends Controller
{
    protected $model = SessionAttendance::class;

    public function __construct(ISessionAttendance $model, IAppSessionAttendance $appModel, IMobileSessionAttendance $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
