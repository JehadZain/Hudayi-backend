<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ActivityTypeController;
use App\Http\Requests\Mobile\V1\MobileActivityTypeCreateRequest;
use App\Http\Requests\Mobile\V1\MobileActivityTypeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileActivityTypeController extends ActivityTypeController
{
    //repoFunctionModel
    public function mobileGetAllActivityTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetActivityTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->activityTypeId)));
    }

    public function mobileCreateActivityType(MobileActivityTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateActivityType(MobileActivityTypeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->activityTypeId));
    }

    public function mobileDeleteActivityType(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->activityTypeId)));
    }
}
