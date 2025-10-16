<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppReportReview;
use App\Interfaces\Common\V1\IReportReview;
use App\Interfaces\Mobile\V1\IMobileReportReview;
use App\Models\Report\ReportReviewer;

//model

class ReportReviewerController extends Controller
{
    protected $model = ReportReviewer::class;

    public function __construct(IReportReview $model, IAppReportReview $appModel, IMobileReportReview $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
