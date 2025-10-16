<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\StudentController;
use App\Http\Requests\App\V1\AppStudentCreateRequest;
use App\Http\Requests\App\V1\AppStudentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppStudentController extends StudentController
{
    public function appGetAllStudents(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appAll($request)
        );
    }

    public function appGetStudentById(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appById(strval($request->studentId))
        );
    }

    public function studentStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->studentStatistics(strval($request->studentId)));
    }

    public function appCreateStudent(AppStudentCreateRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appCreateObject([...$request->safe()])
        );
    }

    public function appUpdateStudent(AppStudentEditRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appUpdateObject([...$request->safe()], $request->studentId)
        );
    }

    public function appTransferStudent(Request $request, $id): JsonResponse
    {
        return $this->response($request, $this->appRepo->appTransferStudent($request->all(), $id));
    }

    public function appDeleteStudent(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appDeleteObject(strval($request->studentId))
        );
    }

    public function appGetStudentGroups(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appGetStudentGroups($request)
        );
    }

    public function appGetAllOrgStudents(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appGetAllOrgStudents($request)
        );
    }
}
