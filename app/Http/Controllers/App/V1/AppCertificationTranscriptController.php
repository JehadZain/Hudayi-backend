<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\CertificateTranscriptController;
use App\Http\Requests\App\V1\AppCertificateTranscriptCreateRequest;
use App\Http\Requests\App\V1\AppCertificateTranscriptEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppCertificationTranscriptController extends CertificateTranscriptController
{
    public function appGetAllCertificationTranscripts(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetCertificationTranscriptById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->transcriptId)));
    }

    public function appCreateCertificationTranscript(AppCertificateTranscriptCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateCertificationTranscript(AppCertificateTranscriptEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->transcriptId));
    }

    public function appDeleteCertificationTranscript(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->transcriptId)));
    }
}
