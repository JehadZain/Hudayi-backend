<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ActivityController;
use App\Http\Requests\Mobile\V1\MobileActivityCreateRequest;
use App\Http\Requests\Mobile\V1\MobileActivityEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileActivityController extends ActivityController
{
    //repoFunctionModel
    public function mobileGetAllActivities(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetActivityById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->activityId)));
    }

    public function mobileCreateActivity(MobileActivityCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateActivity(MobileActivityEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->activityId));
    }

    public function mobileDeleteActivity(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->activityId)));
    }
}
