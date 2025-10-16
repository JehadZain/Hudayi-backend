<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\SessionAttendanceController;
use App\Http\Requests\Mobile\V1\MobileSessionAttendanceCreateRequest;
use App\Http\Requests\Mobile\V1\MobileSessionAttendanceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSessionAttendanceController extends SessionAttendanceController
{
    public function mobileGetAllSessionAttendances(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetSessionAttendanceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->sessionAttendanceId)));
    }

    public function mobileCreateSessionAttendance(MobileSessionAttendanceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->safe()));
    }

    public function mobileUpdateSessionAttendance(MobileSessionAttendanceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->safe(), $request->sessionAttendanceId));
    }

    public function mobileDeleteSessionAttendance(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->sessionAttendanceId)));
    }
}
