<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppActivity;
use App\Interfaces\Common\V1\IActivity;
use App\Interfaces\Mobile\V1\IMobileActivity;
//model
use App\Models\Infos\Activity;

class ActivityController extends Controller
{
    protected $model = Activity::class;

    public function __construct(IActivity $model, IAppActivity $appModel, IMobileActivity $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
