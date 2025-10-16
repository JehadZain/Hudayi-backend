<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppSession;
use App\Interfaces\Common\V1\ISession;
//model
use App\Interfaces\Mobile\V1\IMobileSession;
use App\Models\Infos\Session;

class SessionController extends Controller
{
    protected $model = Session::class;

    public function __construct(ISession $model, IAppSession $appModel, IMobileSession $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
