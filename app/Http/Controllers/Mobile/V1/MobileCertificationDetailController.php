<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\CertificateDetailController;
use App\Http\Requests\Mobile\V1\MobileCertificateDetailCreateRequest;
use App\Http\Requests\Mobile\V1\MobileCertificateDetailEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCertificationDetailController extends CertificateDetailController
{
    public function mobileGetAllCertificationDetails(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetCertificationDetailById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->certificationId)));
    }

    public function mobileCreateCertificationDetail(MobileCertificateDetailCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateCertificationDetail(MobileCertificateDetailEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->certificationId));
    }

    public function mobileDeleteCertificationDetail(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->certificationId)));
    }
}
