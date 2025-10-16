<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\SessionAttendanceController;
use App\Http\Requests\App\V1\AppSessionAttendanceCreateRequest;
use App\Http\Requests\App\V1\AppSessionAttendanceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppSessionAttendanceController extends SessionAttendanceController
{
    public function appGetAllSessionAttendances(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetSessionAttendanceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->sessionAttendanceId)));
    }

    public function appCreateSessionAttendance(AppSessionAttendanceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->safe()));
    }

    public function appUpdateSessionAttendance(AppSessionAttendanceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->safe(), $request->sessionAttendanceId));
    }

    public function appDeleteSessionAttendance(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->sessionAttendanceId)));
    }
}
