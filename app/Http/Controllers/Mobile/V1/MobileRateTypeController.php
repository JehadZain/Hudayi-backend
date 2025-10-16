<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\RateTypeController;
use App\Http\Requests\Mobile\V1\MobileRateTypeCreateRequest;
use App\Http\Requests\Mobile\V1\MobileRateTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileRateTypeController extends RateTypeController
{
    public function mobileGetAllRateTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetRateTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->rateTypeId)));
    }

    public function mobileCreateRateType(MobileRateTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateRateType(MobileRateTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->rateTypeId));
    }

    public function mobileDeleteRateType(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->rateTypeId)));
    }
}
