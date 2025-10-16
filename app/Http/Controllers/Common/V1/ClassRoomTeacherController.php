<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppClassRoomTeacher;
use App\Interfaces\Common\V1\IClassRoomTeacher;
//model
use App\Interfaces\Mobile\V1\IMobileClassRoomTeacher;
use App\Models\Properties\ClassRoomTeacher;

class ClassRoomTeacherController extends Controller
{
    protected $model = ClassRoomTeacher::class;

    public function __construct(IClassRoomTeacher $model, IAppClassRoomTeacher $appModel, IMobileClassRoomTeacher $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
