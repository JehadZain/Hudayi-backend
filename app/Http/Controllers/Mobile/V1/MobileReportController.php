<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ReportController;
use App\Http\Requests\Mobile\V1\MobileReportCreateRequest;
use App\Http\Requests\Mobile\V1\MobileReportEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReportController extends ReportController
{
    public function mobileGetAllReports(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll());
    }

    public function mobileGetReportById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->reportId)));
    }

    public function mobileCreateReport(MobileReportCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateReport(MobileReportEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->reportId));
    }

    public function mobileDeleteReport(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->reportId)));
    }
}
