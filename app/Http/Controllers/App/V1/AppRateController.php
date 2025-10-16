<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\RateController;
use App\Http\Requests\App\V1\AppRateCreateRequest;
use App\Http\Requests\App\V1\AppRateEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppRateController extends RateController
{
    public function appGetAllRates(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetRateById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->rateId)));
    }

    public function appCreateRate(AppRateCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateRate(AppRateEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->rateId));
    }

    public function appDeleteRate(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->rateId)));
    }
}
