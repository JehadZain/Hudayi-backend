<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppOrganization;
use App\Interfaces\Common\V1\IOrganization;
use App\Interfaces\Mobile\V1\IMobileOrganization;
use App\Models\Organization;

class OrganizationController extends Controller
{
    protected $model = Organization::class;

    public function __construct(IOrganization $model, IAppOrganization $appModel, IMobileOrganization $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
