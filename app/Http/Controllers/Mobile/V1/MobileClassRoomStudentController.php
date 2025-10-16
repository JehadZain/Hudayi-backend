<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ClassRoomStudentController;
use App\Http\Requests\Mobile\V1\MobileClassRoomStudentCreateRequest;
use App\Http\Requests\Mobile\V1\MobileClassRoomStudentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileClassRoomStudentController extends ClassRoomStudentController
{
    public function mobileGetAllClassRoomStudents(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetClassRoomStudentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->classRoomStudentId)));
    }

    public function mobileCreateClassRoomStudent(MobileClassRoomStudentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateClassRoomStudent(MobileClassRoomStudentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->classRoomStudentId));
    }

    public function mobileDeleteClassRoomStudent(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->classRoomStudentId)));
    }
}
