<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\JobTitleController;
use App\Http\Requests\Mobile\V1\MobileJobTitleCreateRequest;
use App\Http\Requests\Mobile\V1\MobileJobTitleEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileJobTitleController extends JobTitleController
{
    public function mobileGetAllJobTitles(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetJobTitleById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->jobTitleId)));
    }

    public function mobileCreateJobTitle(MobileJobTitleCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateJobTitle(MobileJobTitleEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->jobTitleId));
    }

    public function mobileDeleteJobTitle(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->jobTitleId)));
    }
}
