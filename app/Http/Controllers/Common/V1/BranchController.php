<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppBranch;
use App\Interfaces\Common\V1\IBranch;
use App\Interfaces\Mobile\V1\IMobileBranch;
use App\Models\Branch;

class BranchController extends Controller
{
    protected $model = Branch::class;

    public function __construct(IBranch $model, IAppBranch $appModel, IMobileBranch $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
