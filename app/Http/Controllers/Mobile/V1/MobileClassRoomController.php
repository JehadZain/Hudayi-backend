<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ClassRoomController;
use App\Http\Requests\Mobile\V1\MobileClassRoomCreateRequest;
use App\Http\Requests\Mobile\V1\MobileClassRoomEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileClassRoomController extends ClassRoomController
{
    public function mobileGetAllClassRooms(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileAllSchoolClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAllSchoolClassroom($request));
    }

    public function mobileAllMosqueClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAllMosqueClassroom($request));
    }

    public function getAllClassesNotApproved(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllClassesNotApproved($request));
    }

    public function getAllPendingSchoolClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllPendingSchoolClasses($request));
    }

    public function getAllPendingMosqueClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllPendingMosqueClasses($request));
    }

    public function getAllApprovedClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllApprovedClasses($request));
    }

    public function getAllApprovedSchoolClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllApprovedSchoolClasses($request));
    }

    public function getAllApprovedMosqueClasses(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getAllApprovedMosqueClasses($request));
    }

    public function mobileGetClassRoomById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->classRoomId)));
    }

    public function mobileCreateClassRoom(MobileClassRoomCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateClassRoom(MobileClassRoomEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->classRoomId));
    }

    public function mobileDeleteClassRoom(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->classRoomId)));
    }

    public function mobileGetAllStudentsWithoutClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllStudentsWithoutClassroom(strval($request)));
    }

    public function mobileGetAllTeachersWithoutClassroom(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllTeachersWithoutClassroom(strval($request)));
    }

    public function classStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->classStatistics(strval($request->classRoomId)));
    }

    public function getClassRoomBooks(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getClassRoomBooks(strval($request->classRoomId)));
    }

    public function mobileCreatClassRoomBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreatClassRoomBook(strval($request->bookId), strval($request->classRoomId)));
    }

    public function mobileDeleteClassRoomBook(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteClassRoomBook(strval($request->bookId), strval($request->classRoomId)));
    }
}
