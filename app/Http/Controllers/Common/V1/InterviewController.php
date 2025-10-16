<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppInterview;
use App\Interfaces\Common\V1\IInterview;
//model
use App\Interfaces\Mobile\V1\IMobileInterview;
use App\Models\Interview;

class InterviewController extends Controller
{
    protected $model = Interview::class;

    public function __construct(IInterview $model, IAppInterview $appModel, IMobileInterview $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
