<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppActivityType;
use App\Interfaces\Common\V1\IActivityType;
use App\Interfaces\Mobile\V1\IMobileActivityType;
//model
use App\Models\ActivityType;

class ActivityTypeController extends Controller
{
    protected $model = ActivityType::class;

    public function __construct(IActivityType $model, IAppActivityType $appModel, IMobileActivityType $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
