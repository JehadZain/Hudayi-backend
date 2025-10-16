<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ReportTypeController;
use App\Http\Requests\App\V1\AppReportEditRequest;
use App\Http\Requests\App\V1\AppReportTypeCreateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReportTypeController extends ReportTypeController
{
    public function appGetAllReportTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll());
    }

    public function appGetReportTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->reportTypeId)));
    }

    public function appCreateReportType(AppReportTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateReportType(AppReportEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->reportTypeId));
    }

    public function appDeleteReportType(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->reportTypeId)));
    }
}
