<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ReportReviewerController;
use App\Http\Requests\Mobile\V1\MobileReportReviewCreateRequest;
use App\Http\Requests\Mobile\V1\MobileReportReviewEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReportReviewerController extends ReportReviewerController
{
    public function mobileGetAllReportReviewers(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll());
    }

    public function mobileGetReportReviewerById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->reviewerId)));
    }

    public function mobileCreateReportReviewer(MobileReportReviewCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateReportReviewer(MobileReportReviewEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->reviewerId));
    }

    public function mobileDeleteReportReviewer(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->reviewerId)));
    }
}
