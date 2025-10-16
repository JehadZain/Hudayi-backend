<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\PropertyController;
use App\Http\Requests\App\V1\AppPropertyCreateRequest;
use App\Http\Requests\App\V1\AppPropertyEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppPropertyController extends PropertyController
{
    public function appGetAllProperties(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetAllMosques(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllMosques($request));
    }

    public function appGetAllSchools(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllSchools($request));
    }

    public function appGetPropertyById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->propertyId)));
    }

    public function allPropertyStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->allPropertyStatistics($request));
    }

    public function propertyStatistics(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->propertyStatistics(strval($request->propertyId)));
    }

    public function appGetAllPropertyStudents(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllPropertyStudents(strval($request->propertyId)));
    }

    public function appGetAllPropertyTeachers(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllPropertyTeachers(strval($request->propertyId)));
    }

    public function getStudentsWithoutClassroomByPropertyId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getStudentsWithoutClassroomByPropertyId(strval($request->propertyId)));
    }

    public function getTeachersWithoutClassroomByPropertyId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->getTeachersWithoutClassroomByPropertyId(strval($request->propertyId)));
    }

    //    public function appGetAllStudents(Request $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appGetAllStudents(strval($request->propertyId)));
    //    }

    public function appCreateProperty(AppPropertyCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateProperty(AppPropertyEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->propertyId));
    }

    public function appDeleteProperty(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->propertyId)));
    }
}
