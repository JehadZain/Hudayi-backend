<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppReport;
use App\Interfaces\Common\V1\IReport;
use App\Interfaces\Mobile\V1\IMobileReport;
use App\Models\Morphs\Report;

//model

class ReportController extends Controller
{
    protected $model = Report::class;

    public function __construct(IReport $model, IAppReport $appModel, IMobileReport $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
