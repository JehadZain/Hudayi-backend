<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ActivityController;
use App\Http\Requests\App\V1\AppActivityCreateRequest;
use App\Http\Requests\App\V1\AppActivityEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppActivityController extends ActivityController
{
    //repoFunctionModel
    public function appGetAllActivities(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetActivityById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->activityId)));
    }

    public function appGetAllActivitiesByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllActivitiesByTeacherId(strval($request->teacherId)));
    }

    public function appCreateActivity(AppActivityCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateActivity(AppActivityEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->activityId));
    }

    public function appDeleteActivity(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->activityId)));
    }
}
