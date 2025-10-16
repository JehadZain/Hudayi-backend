<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\CertificateDetailController;
use App\Http\Requests\App\V1\AppCertificateDetailCreateRequest;
use App\Http\Requests\App\V1\AppCertificateDetailEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppCertificationDetailController extends CertificateDetailController
{
    public function appGetAllCertificationDetails(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetCertificationDetailById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->certificationId)));
    }

    public function appCreateCertificationDetail(AppCertificateDetailCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateCertificationDetail(AppCertificateDetailEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->certificationId));
    }

    public function appDeleteCertificationDetail(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->certificationId)));
    }
}
