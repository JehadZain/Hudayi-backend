<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppRateType;
use App\Interfaces\Common\V1\IRateType;
use App\Interfaces\Mobile\V1\IMobileRateType;
use App\Models\Infos\RateType;

//model

class RateTypeController extends Controller
{
    protected $model = RateType::class;

    public function __construct(IRateType $model, IAppRateType $appModel, IMobileRateType $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
