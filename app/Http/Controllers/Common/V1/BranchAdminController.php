<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppBranchAdmin;
use App\Interfaces\Common\V1\IBranchAdmin;
use App\Interfaces\Mobile\V1\IMobileBranchAdmin;
use App\Models\BranchAdmin;

class BranchAdminController extends Controller
{
    protected $model = BranchAdmin::class;

    public function __construct(IBranchAdmin $model, IAppBranchAdmin $appModel, IMobileBranchAdmin $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
