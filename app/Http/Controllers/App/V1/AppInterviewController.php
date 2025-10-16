<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\InterviewController;
use App\Http\Requests\App\V1\AppInterviewCreateRequest;
use App\Http\Requests\App\V1\AppInterviewEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppInterviewController extends InterviewController
{
    //repoFunctionModel
    public function appGetAllInterviews(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetInterviewById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->interviewId)));
    }

    public function appGetAllInterviewsByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllInterviewsByTeacherId(strval($request->teacherId)));
    }

    public function appCreateInterview(AppInterviewCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateInterview(AppInterviewEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->interviewId));
    }

    public function appDeleteInterview(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->interviewId)));
    }
}
