<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\app\v1\IAppCertificateType;
use App\Interfaces\Common\V1\ICertificateType;
use App\Interfaces\Mobile\V1\IMobileCertificateType;
use App\Models\Infos\CertificateType;

class CertificateTypeController extends Controller
{
    protected $model = CertificateType::class;

    public function __construct(ICertificateType $model, IAppCertificateType $appModel, IMobileCertificateType $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
