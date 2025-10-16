<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\GradeController;
use App\Http\Requests\Mobile\V1\MobileGradeCreateRequest;
use App\Http\Requests\Mobile\V1\MobileGradeEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileGradeController extends GradeController
{
    public function mobileGetAllGrades(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetGradeById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->gradeId)));
    }

    public function mobileGetGradeStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetGradeStatistics(strval($request->gradeId)));
    }

    public function mobileCreateGrade(MobileGradeCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateGrade(MobileGradeEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->gradeId));
    }

    public function mobileDeleteGrade(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->gradeId)));
    }
}
