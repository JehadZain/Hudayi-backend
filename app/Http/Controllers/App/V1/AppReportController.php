<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ReportController;
use App\Http\Requests\App\V1\AppReportCreateRequest;
use App\Http\Requests\App\V1\AppReportEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReportController extends ReportController
{
    public function appGetAllReports(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll());
    }

    public function appGetReportById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->reportId)));
    }

    public function appCreateReport(AppReportCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateReport(AppReportEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->reportId));
    }

    public function appDeleteReport(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->reportId)));
    }
}
