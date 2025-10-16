<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppOrganizationAdmin;
use App\Interfaces\Common\V1\IOrganizationAdmin;
use App\Interfaces\Mobile\V1\IMobileOrganizationAdmin;
use App\Models\OrganizationAdmin;

class OrganizationAdminController extends Controller
{
    protected $model = OrganizationAdmin::class;

    public function __construct(IOrganizationAdmin $model, IAppOrganizationAdmin $appModel, IMobileOrganizationAdmin $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
