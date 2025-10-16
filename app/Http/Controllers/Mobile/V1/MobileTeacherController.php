<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\TeacherController;
use App\Http\Requests\Mobile\V1\MobileTeacherCreateRequest;
use App\Http\Requests\Mobile\V1\MobileTeacherEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileTeacherController extends TeacherController
{
    public function mobileGetAllTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetAllOrgTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllOrgTeacher($request));
    }

    public function mobileGetTeacherById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->teacherId)));
    }

    public function mobileCreateTeacher(MobileTeacherCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateTeacher(MobileTeacherEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->teacherId));
    }

    public function mobileTransferTeacher(Request $request, $id): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileTransferTeacher($request->all(), $id));
    }

    public function mobileDeleteTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->teacherId)));
    }

    //    public function mobileTeacherClassRooms(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileGetTeacherClassRooms($request));
    //    }

    public function teacherStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->teacherStatistics(strval($request->teacherId)));
    }
}
