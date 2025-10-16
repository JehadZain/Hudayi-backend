<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\PropertyController;
use App\Http\Requests\Mobile\V1\MobilePropertyCreateRequest;
use App\Http\Requests\Mobile\V1\MobilePropertyEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilePropertyController extends PropertyController
{
    public function mobileGetAllProperties(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetAllMosques(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllMosques($request));
    }

    public function mobileGetAllSchools(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllSchools($request));
    }

    public function mobileGetPropertyById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->propertyId)));
    }

    public function propertyClassRooms(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->propertyClassRooms(strval($request->propertyId)));
    }

    public function allPropertyStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->allPropertyStatistics($request));
    }

    public function mobilePropertyStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobilePropertyStatistics($request->propertyId));
    }

    public function mobileGetAllPropertyStudents(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllPropertyStudents(strval($request->propertyId)));
    }

    public function mobileGetAllPropertyTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllPropertyTeachers(strval($request->propertyId)));
    }

    public function getStudentsWithoutClassroomByPropertyId(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getStudentsWithoutClassroomByPropertyId(strval($request->propertyId)));
    }

    public function getTeachersWithoutClassroomByPropertyId(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->getTeachersWithoutClassroomByPropertyId(strval($request->propertyId)));
    }

    //    public function mobileGetAllStudents(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileGetAllStudents(strval($request->propertyId)));
    //    }

    public function mobileCreateProperty(MobilePropertyCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateProperty(MobilePropertyEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->propertyId));
    }

    public function mobileDeleteProperty(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->propertyId)));
    }
}
