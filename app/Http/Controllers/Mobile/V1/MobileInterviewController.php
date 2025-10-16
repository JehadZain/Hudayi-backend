<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\InterviewController;
use App\Http\Requests\Mobile\V1\MobileInterviewCreateRequest;
use App\Http\Requests\Mobile\V1\MobileInterviewEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileInterviewController extends InterviewController
{
    //repoFunctionModel
    public function mobileGetAllInterviews(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetInterviewById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->interviewId)));
    }

    public function mobileCreateInterview(MobileInterviewCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateInterview(MobileInterviewEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->interviewId));
    }

    public function mobileDeleteInterview(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->interviewId)));
    }
}
