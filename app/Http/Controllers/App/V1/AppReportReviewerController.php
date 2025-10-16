<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ReportReviewerController;
use App\Http\Requests\App\V1\AppReportReviewCreateRequest;
use App\Http\Requests\App\V1\AppReportReviewEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReportReviewerController extends ReportReviewerController
{
    public function appGetAllReportReviewers(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll());
    }

    public function appGetReportReviewerById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->reviewerId)));
    }

    public function appCreateReportReviewer(AppReportReviewCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateReportReviewer(AppReportReviewEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->reviewerId));
    }

    public function appDeleteReportReviewer(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->reviewerId)));
    }
}
