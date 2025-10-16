<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\CertificationController;
use App\Http\Requests\App\V1\AppCertificationCreateRequest;
use App\Http\Requests\App\V1\AppCertificationEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppCertificationController extends CertificationController
{
    public function appGetAllCertifications(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetCertificationById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->certificationId)));
    }

    public function appCreateCertification(AppCertificationCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateCertification(AppCertificationEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->certificationId));
    }

    public function appDeleteCertification(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->certificationId)));
    }
}
