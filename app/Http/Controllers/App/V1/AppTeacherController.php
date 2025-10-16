<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\TeacherController;
use App\Http\Requests\App\V1\AppTeacherCreateRequest;
use App\Http\Requests\App\V1\AppTeacherEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppTeacherController extends TeacherController
{
    public function appGetAllTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetAllOrgTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllOrgTeacher($request));
    }

    public function appGetTeacherById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->teacherId)));
    }

    public function appCreateTeacher(AppTeacherCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateTeacher(AppTeacherEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->teacherId));
    }

    public function appTransferTeacher(Request $request, $id): JsonResponse
    {
        return $this->response($request, $this->appRepo->appTransferTeacher($request->all(), $id));
    }

    public function appDeleteTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->teacherId)));
    }

    //    public function appTeacherClassRooms(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appGetTeacherClassRooms($request));
    //    }

    public function teacherStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->teacherStatistics(strval($request->teacherId)));
    }
}
