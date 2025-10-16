<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ReportTypeController;
use App\Http\Requests\Mobile\V1\MobileReportEditRequest;
use App\Http\Requests\Mobile\V1\MobileReportTypeCreateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReportTypeController extends ReportTypeController
{
    public function mobileGetAllReportTypes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll());
    }

    public function mobileGetReportTypeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->reportTypeId)));
    }

    public function mobileCreateReportType(MobileReportTypeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateReportType(MobileReportEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->reportTypeId));
    }

    public function mobileDeleteReportType(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->reportTypeId)));
    }
}
