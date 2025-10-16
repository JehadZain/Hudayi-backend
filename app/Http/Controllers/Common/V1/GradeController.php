<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppGrade;
use App\Interfaces\Common\V1\IGrade;
//model
use App\Interfaces\Mobile\V1\IMobileGrade;
use App\Models\Infos\Grade;

class GradeController extends Controller
{
    protected $model = Grade::class;

    public function __construct(IGrade $model, IAppGrade $appModel, IMobileGrade $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
