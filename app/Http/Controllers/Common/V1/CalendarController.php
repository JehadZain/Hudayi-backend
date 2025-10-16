<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppCalendar;
use App\Interfaces\Common\V1\ICalendar;
use App\Interfaces\Mobile\V1\IMobileCalendar;
use App\Models\Calendar;

class CalendarController extends Controller
{
    protected $model = Calendar::class;

    public function __construct(ICalendar $student, IAppCalendar $appModel, IMobileCalendar $mobModel)
    {
        $this->baseRepo = $student;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
