<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\StatusController;
use App\Http\Requests\App\V1\AppAttendanceCreateRequest;
use App\Http\Requests\App\V1\AppAttendanceEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppAttendanceController extends StatusController
{
    //repoFunctionModel
    public function appGetAllAttendances(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetAttendanceById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->attendanceId)));
    }

    public function appCreateAttendance(AppAttendanceCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->safe()));
    }

    public function appUpdateAttendance(AppAttendanceEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->safe(), $request->attendanceId));
    }

    public function appDeleteAttendance(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->attendanceId)));
    }
}
