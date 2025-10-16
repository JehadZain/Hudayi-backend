<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppReportType;
use App\Interfaces\Common\V1\IReportType;
use App\Interfaces\Mobile\V1\IMobileReportType;
use App\Models\Report\ReportType;

//model

class ReportTypeController extends Controller
{
    protected $model = ReportType::class;

    public function __construct(IReportType $model, IAppReportType $appModel, IMobileReportType $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
