<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\GradeController;
use App\Http\Requests\App\V1\AppGradeCreateRequest;
use App\Http\Requests\App\V1\AppGradeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppGradeController extends GradeController
{
    public function appGetAllGrades(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetGradeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->gradeId)));
    }

    public function appGetGradeStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetGradeStatistics(strval($request->gradeId)));
    }

    public function appCreateGrade(AppGradeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateGrade(AppGradeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->gradeId));
    }

    public function appDeleteGrade(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->gradeId)));
    }
}
