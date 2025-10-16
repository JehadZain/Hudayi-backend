<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppClassRoom;
use App\Interfaces\Common\V1\IClassRoom;
//model
use App\Interfaces\Mobile\V1\IMobileClassRoom;
use App\Models\Properties\ClassRoom;

class ClassRoomController extends Controller
{
    protected $model = ClassRoom::class;

    public function __construct(IClassRoom $model, IAppClassRoom $appModel, IMobileClassRoom $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
