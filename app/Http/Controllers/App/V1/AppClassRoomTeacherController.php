<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ClassRoomTeacherController;
use App\Http\Requests\App\V1\AppClassRoomTeacherCreateRequest;
use App\Http\Requests\App\V1\AppClassRoomTeacherEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppClassRoomTeacherController extends ClassRoomTeacherController
{
    public function appGetAllClassRoomTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetClassRoomTeacherById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->classRoomTeacherId)));
    }

    public function appCreateClassRoomTeacher(AppClassRoomTeacherCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateClassRoomTeacher(AppClassRoomTeacherEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->classRoomTeacherId));
    }

    public function appDeleteClassRoomTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->classRoomTeacherId)));
    }
}
