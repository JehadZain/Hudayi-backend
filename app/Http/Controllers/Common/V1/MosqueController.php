<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppMosque;
use App\Interfaces\Common\V1\IMosque;
use App\Interfaces\Mobile\V1\IMobileMosque;
use App\Models\properties\Mosque;

class MosqueController extends Controller
{
    protected $model = Mosque::class;

    public function __construct(IMosque $model, IAppMosque $appModel, IMobileMosque $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
