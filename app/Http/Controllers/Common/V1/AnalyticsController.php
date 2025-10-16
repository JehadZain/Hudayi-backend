<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppAnalytics;
use App\Interfaces\Common\V1\IAnalytics;
use App\Interfaces\Mobile\V1\IMobileAnalytics;
//model
use App\Models\Analytics;

class AnalyticsController extends Controller
{
    protected $model = Analytics::class;

    public function __construct(IAnalytics $model, IAppAnalytics $appModel, IMobileAnalytics $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
