<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ReportContentController;
use App\Http\Requests\App\V1\AppReportContentCreateRequest;
use App\Http\Requests\App\V1\AppReportContentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReportContentController extends ReportContentController
{
    public function appGetAllReportContents(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll());
    }

    public function appGetReportContentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->contentId)));
    }

    public function appCreateReportContent(AppReportContentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateReportContent(AppReportContentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->contentId));
    }

    public function appDeleteReportContent(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->contentId)));
    }
}
