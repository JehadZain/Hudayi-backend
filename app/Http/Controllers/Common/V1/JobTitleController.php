<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppJobTitle;
use App\Interfaces\Common\V1\IJobTitle;
use App\Interfaces\Mobile\V1\IMobileJobTitle;
use App\Models\Infos\JobTitle;

class JobTitleController extends Controller
{
    protected $model = JobTitle::class;

    public function __construct(IJobTitle $model, IAppJobTitle $appModel, IMobileJobTitle $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
