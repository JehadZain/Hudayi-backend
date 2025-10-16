<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\CertificateTypeController;
use App\Http\Requests\Mobile\V1\MobileCertificateTypeCreateRequest;
use App\Http\Requests\Mobile\V1\MobileCertificateTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCertificateTypeController extends CertificateTypeController
{
    public function mobileGetAllCertificationTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetCertificationTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->certificationId)));
    }

    public function mobileCreateCertificationType(MobileCertificateTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateCertificationType(MobileCertificateTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->certificationId));
    }

    public function mobileDeleteCertificationType(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->certificationId)));
    }
}
