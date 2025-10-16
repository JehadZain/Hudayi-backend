<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ReportContentController;
use App\Http\Requests\Mobile\V1\MobileReportContentCreateRequest;
use App\Http\Requests\Mobile\V1\MobileReportContentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileReportContentController extends ReportContentController
{
    public function mobileGetAllReportContents(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll());
    }

    public function mobileGetReportContentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->contentId)));
    }

    public function mobileCreateReportContent(MobileReportContentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateReportContent(MobileReportContentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->contentId));
    }

    public function mobileDeleteReportContent(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->contentId)));
    }
}
