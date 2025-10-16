<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppPropertyAdmin;
//model
use App\Interfaces\Common\V1\IPropertyAdmin;
use App\Interfaces\Mobile\V1\IMobilePropertyAdmin;
use App\Models\PropertyAdmin;

class PropertyAdminController extends Controller
{
    protected $model = PropertyAdmin::class;

    public function __construct(IPropertyAdmin $model, IAppPropertyAdmin $appModel, IMobilePropertyAdmin $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
