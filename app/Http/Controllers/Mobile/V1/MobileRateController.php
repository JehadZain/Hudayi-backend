<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\RateController;
use App\Http\Requests\Mobile\V1\MobileQuizCreateRequest;
use App\Http\Requests\Mobile\V1\MobileQuizEditRequest;
use App\Http\Requests\Mobile\V1\MobileRateCreateRequest;
use App\Http\Requests\Mobile\V1\MobileRateEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileRateController extends RateController
{
    public function mobileGetAllRates(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }


    public function mobileGetRateById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->rateId)));
    }

    public function mobileCreateRate(MobileRateCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateRate(MobileRateEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->rateId));
    }

    public function mobileDeleteRate(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->rateId)));
    }
}
