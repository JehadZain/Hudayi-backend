<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ClassRoomController;
use App\Http\Requests\App\V1\AppClassRoomCreateRequest;
use App\Http\Requests\App\V1\AppClassRoomEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppClassRoomController extends ClassRoomController
{
    public function appGetAllClassRooms(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appAllSchoolClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAllSchoolClassroom($request));
    }

    public function appAllMosqueClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAllMosqueClassroom($request));
    }

    public function getAllClassesNotApproved(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllClassesNotApproved($request));
    }

    public function getAllPendingSchoolClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllPendingSchoolClasses($request));
    }

    public function getAllPendingMosqueClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllPendingMosqueClasses($request));
    }

    public function getAllApprovedClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllApprovedClasses($request));
    }

    public function getAllApprovedSchoolClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllApprovedSchoolClasses($request));
    }

    public function getAllApprovedMosqueClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getAllApprovedMosqueClasses($request));
    }

    public function appGetClassRoomById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->classRoomId)));
    }

    public function appAllClassesByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAllClassesByTeacherId(strval($request->teacherId)));
    }

    public function appCreateClassRoom(AppClassRoomCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateClassRoom(AppClassRoomEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->classRoomId));
    }

    public function appDeleteClassRoom(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->classRoomId)));
    }

    public function appGetAllStudentsWithoutClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllStudentsWithoutClassroom(strval($request)));
    }

    public function appGetAllTeachersWithoutClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllTeachersWithoutClassroom(strval($request)));
    }

    public function classStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->classStatistics(strval($request->classRoomId)));
    }

    public function getClassRoomBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getClassRoomBooks(strval($request->classRoomId)));
    }

    public function appCreatClassRoomBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreatClassRoomBook(strval($request->bookId), strval($request->classRoomId)));
    }

    public function appDeleteClassRoomBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteClassRoomBook(strval($request->bookId), strval($request->classRoomId)));
    }
}
