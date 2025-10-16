<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ClassRoomStudentController;
use App\Http\Requests\App\V1\AppClassRoomStudentCreateRequest;
use App\Http\Requests\App\V1\AppClassRoomStudentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppClassRoomStudentController extends ClassRoomStudentController
{
    public function appGetAllClassRoomStudents(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetClassRoomStudentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->classRoomStudentId)));
    }

    public function appCreateClassRoomStudent(AppClassRoomStudentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateClassRoomStudent(AppClassRoomStudentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->classRoomStudentId));
    }

    public function appDeleteClassRoomStudent(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->classRoomStudentId)));
    }
}
