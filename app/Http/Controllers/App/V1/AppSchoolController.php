<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\SchoolController;
use App\Http\Requests\App\V1\AppSchoolCreateRequest;
use App\Http\Requests\App\V1\AppSchoolEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppSchoolController extends SchoolController
{
    public function appGetAllSchools(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetSchoolById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->schoolId)));
    }

    public function appCreateSchool(AppSchoolCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateSchool(AppSchoolEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->schoolId));
    }

    public function appDeleteSchool(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->schoolId)));
    }
}
