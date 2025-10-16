<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppCertificateDetail;
use App\Interfaces\Common\V1\ICertificateDetail;
use App\Interfaces\Mobile\V1\IMobileCertificateDetail;
use App\Models\Infos\CertificateDetail;

class CertificateDetailController extends Controller
{
    protected $model = CertificateDetail::class;

    public function __construct(ICertificateDetail $model, IAppCertificateDetail $appModel, IMobileCertificateDetail $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
