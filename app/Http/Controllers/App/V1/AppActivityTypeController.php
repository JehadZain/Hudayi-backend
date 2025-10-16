<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ActivityTypeController;
use App\Http\Requests\App\V1\AppActivityTypeCreateRequest;
use App\Http\Requests\App\V1\AppActivityTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppActivityTypeController extends ActivityTypeController
{
    //repoFunctionModel
    public function appGetAllActivityTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetActivityTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->activityTypeId)));
    }

    public function appCreateActivityType(AppActivityTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateActivityType(AppActivityTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->activityTypeId));
    }

    public function appDeleteActivityType(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->activityTypeId)));
    }
}
