<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppClassRoomStudent;
use App\Interfaces\Common\V1\IClassRoomStudent;
//model
use App\Interfaces\Mobile\V1\IMobileClassRoomStudent;
use App\Models\Properties\ClassRoomStudent;

class ClassRoomStudentController extends Controller
{
    protected $model = ClassRoomStudent::class;

    public function __construct(IClassRoomStudent $model, IAppClassRoomStudent $appModel, IMobileClassRoomStudent $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
