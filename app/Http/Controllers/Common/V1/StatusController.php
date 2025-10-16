<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IStatus;
use App\Interfaces\App\V1\IAppStatus;
use App\Interfaces\Mobile\V1\IMobileStatus;
use App\Models\Infos\Status;

class StatusController extends Controller
{
    protected $model = Status::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IStatus $status, IAppStatus $appStatus, IMobileStatus $mobileStatus)
    {
        $this->baseRepo = $status;
        $this->appRepo = $appStatus;
        $this->mobileRepo = $mobileStatus;
    }
}
