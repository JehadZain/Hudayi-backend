<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\StatusController;
use App\Http\Requests\Mobile\V1\MobileAttendanceCreateRequest;
use App\Http\Requests\Mobile\V1\MobileAttendanceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileAttendanceController extends StatusController
{
    //repoFunctionModel
    public function mobileGetAllAttendances(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetAttendanceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->attendanceId)));
    }

    public function mobileCreateAttendance(MobileAttendanceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->safe()));
    }

    public function mobileUpdateAttendance(MobileAttendanceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->safe(), $request->attendanceId));
    }

    public function mobileDeleteAttendance(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->attendanceId)));
    }
}
