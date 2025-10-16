<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\CertificationController;
use App\Http\Requests\Mobile\V1\MobileCertificationCreateRequest;
use App\Http\Requests\Mobile\V1\MobileCertificationEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCertificationController extends CertificationController
{
    public function mobileGetAllCertifications(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetCertificationById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->certificationId)));
    }

    public function mobileCreateCertification(MobileCertificationCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateCertification(MobileCertificationEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->certificationId));
    }

    public function mobileDeleteCertification(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->certificationId)));
    }
}
