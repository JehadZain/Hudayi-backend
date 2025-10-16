<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\SchoolController;
use App\Http\Requests\Mobile\V1\MobileSchoolCreateRequest;
use App\Http\Requests\Mobile\V1\MobileSchoolEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSchoolController extends SchoolController
{
    public function mobileGetAllSchools(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetSchoolById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->schoolId)));
    }

    public function mobileCreateSchool(MobileSchoolCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateSchool(MobileSchoolEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->schoolId));
    }

    public function mobileDeleteSchool(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->schoolId)));
    }
}
