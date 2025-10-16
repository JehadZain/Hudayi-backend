<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ClassRoomTeacherController;
use App\Http\Requests\Mobile\V1\MobileClassRoomTeacherCreateRequest;
use App\Http\Requests\Mobile\V1\MobileClassRoomTeacherEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileClassRoomTeacherController extends ClassRoomTeacherController
{
    public function mobileGetAllClassRoomTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetClassRoomTeacherById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->classRoomTeacherId)));
    }

    public function mobileCreateClassRoomTeacher(MobileClassRoomTeacherCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateClassRoomTeacher(MobileClassRoomTeacherEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->classRoomTeacherId));
    }

    public function mobileDeleteClassRoomTeacher(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->classRoomTeacherId)));
    }
}
