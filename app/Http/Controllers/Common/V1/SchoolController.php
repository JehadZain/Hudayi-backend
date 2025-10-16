<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppSchool;
use App\Interfaces\Common\V1\ISchool;
use App\Interfaces\Mobile\V1\IMobileSchool;
use App\Models\properties\School;

class SchoolController extends Controller
{
    protected $model = School::class;

    public function __construct(ISchool $model, IAppSchool $appModel, IMobileSchool $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
