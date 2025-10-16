<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\RateTypeController;
use App\Http\Requests\App\V1\AppRateTypeCreateRequest;
use App\Http\Requests\App\V1\AppRateTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppRateTypeController extends RateTypeController
{
    public function appGetAllRateTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetRateTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->rateTypeId)));
    }

    public function appCreateRateType(AppRateTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateRateType(AppRateTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->rateTypeId));
    }

    public function appDeleteRateType(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->rateTypeId)));
    }
}
