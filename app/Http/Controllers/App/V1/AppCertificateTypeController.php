<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\CertificateTypeController;
use App\Http\Requests\App\V1\AppCertificateTypeCreateRequest;
use App\Http\Requests\App\V1\AppCertificateTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppCertificateTypeController extends CertificateTypeController
{
    public function appGetAllCertificationTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetCertificationTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->certificationId)));
    }

    public function appCreateCertificationType(AppCertificateTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateCertificationType(AppCertificateTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->certificationId));
    }

    public function appDeleteCertificationType(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->certificationId)));
    }
}
