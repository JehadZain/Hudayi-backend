<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ReferenceController;
use App\Http\Requests\Mobile\V1\MobileReferenceCreateRequest;
use App\Http\Requests\Mobile\V1\MobileReferenceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReferenceController extends ReferenceController
{
    public function mobileGetAllReferences(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetReferenceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->referenceId)));
    }

    public function mobileCreateReference(MobileReferenceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateReference(MobileReferenceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->referenceId));
    }

    public function mobileDeleteReference(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->referenceId)));
    }
}
