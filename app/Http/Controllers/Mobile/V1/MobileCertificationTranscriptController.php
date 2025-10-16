<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\CertificateTranscriptController;
use App\Http\Requests\Mobile\V1\MobileCertificateTranscriptCreateRequest;
use App\Http\Requests\Mobile\V1\MobileCertificateTranscriptEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCertificationTranscriptController extends CertificateTranscriptController
{
    public function mobileGetAllCertificationTranscripts(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetCertificationTranscriptById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->transcriptId)));
    }

    public function mobileCreateCertificationTranscript(MobileCertificateTranscriptCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateCertificationTranscript(MobileCertificateTranscriptEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->transcriptId));
    }

    public function mobileDeleteCertificationTranscript(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->transcriptId)));
    }
}
