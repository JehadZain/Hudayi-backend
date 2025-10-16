<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppCertification;
use App\Interfaces\Common\V1\ICertification;
use App\Interfaces\Mobile\V1\IMobileCertification;
use App\Models\Infos\Certification;

class CertificationController extends Controller
{
    protected $model = Certification::class;

    public function __construct(ICertification $model, IAppCertification $appModel, IMobileCertification $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
