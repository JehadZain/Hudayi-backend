<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppSubject;
use App\Interfaces\Common\V1\ISubject;
//model
use App\Interfaces\Mobile\V1\IMobileSubject;
use App\Models\Infos\Subject;

class SubjectController extends Controller
{
    protected $model = Subject::class;

    public function __construct(ISubject $model, IAppSubject $appModel, IMobileSubject $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
