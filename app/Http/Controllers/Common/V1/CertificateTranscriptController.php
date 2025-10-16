<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppCertificateTranscript;
use App\Interfaces\Common\V1\ICertificateTranscript;
use App\Interfaces\Mobile\V1\IMobileCertificateTranscript;
use App\Models\Infos\CertificateTranscript;

class CertificateTranscriptController extends Controller
{
    protected $model = CertificateTranscript::class;

    public function __construct(ICertificateTranscript $model, IAppCertificateTranscript $appModel, IMobileCertificateTranscript $mobModel)
    {
        $this->baseRepo = $model;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;

    }
}
