<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppProperty;
use App\Interfaces\Common\V1\IProperty;
//model
use App\Interfaces\Mobile\V1\IMobileProperty;
use App\Models\Properties\Property;

class PropertyController extends Controller
{
    protected $model = Property::class;

    public function __construct(IProperty $model, IAppProperty $appModel, IMobileProperty $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }
}
