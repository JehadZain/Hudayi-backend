<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppReportContent;
use App\Interfaces\Common\V1\IReportContent;
use App\Interfaces\Mobile\V1\IMobileReportContent;
use App\Models\Report\ReportContent;

class ReportContentController extends Controller
{
    protected $model = ReportContent::class;

    public function __construct(IReportContent $model, IAppReportContent $appModel, IMobileReportContent $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
