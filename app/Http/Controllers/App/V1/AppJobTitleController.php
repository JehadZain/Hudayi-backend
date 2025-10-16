<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\JobTitleController;
use App\Http\Requests\App\V1\AppJobTitleCreateRequest;
use App\Http\Requests\App\V1\AppJobTitleEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppJobTitleController extends JobTitleController
{
    public function appGetAllJobTitles(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetJobTitleById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->jobTitleId)));
    }

    public function appCreateJobTitle(AppJobTitleCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateJobTitle(AppJobTitleEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->jobTitleId));
    }

    public function appDeleteJobTitle(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->jobTitleId)));
    }
}
