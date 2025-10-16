<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\StudentController;
use App\Http\Requests\Mobile\V1\MobileStudentCreateRequest;
use App\Http\Requests\Mobile\V1\MobileStudentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileStudentController extends StudentController
{
    public function mobileGetAllStudents(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileAll($request)
        );
    }

    public function mobileGetStudentById(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileById(strval($request->studentId))
        );
    }

    public function studentStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->studentStatistics(strval($request->studentId)));
    }

    public function mobileCreateStudent(MobileStudentCreateRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileCreateObject([...$request->safe()])
        );
    }

    public function mobileUpdateStudent(MobileStudentEditRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->studentId)
        );
    }

    public function mobileTransferStudent(Request $request, $id): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileTransferStudent($request->all(), $id));
    }

    public function mobileDeleteStudent(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileDeleteObject(strval($request->studentId))
        );
    }

    public function mobileGetStudentGroups(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileGetStudentGroups($request)
        );
    }

    public function mobileGetAllOrgStudents(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileGetAllOrgStudents($request)
        );
    }
}
