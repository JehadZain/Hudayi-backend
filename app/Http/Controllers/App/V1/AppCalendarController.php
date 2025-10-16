<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\CalendarController;
use App\Http\Requests\App\V1\AppCalendarCreateRequest;
use App\Http\Requests\App\V1\AppCalendarEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppCalendarController extends CalendarController
{
    public function appGetAllCalendars(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appAll($request)
        );
    }

    public function appGetCalendarById(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appById(strval($request->calendarId))
        );
    }

    public function appCreateCalendar(AppCalendarCreateRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appCreateObject([...$request->safe()])
        );
    }

    public function appUpdateCalendar(AppCalendarEditRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appUpdateObject([...$request->safe()], $request->calendarId)
        );
    }

    public function appDeleteCalendar(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->appRepo->appDeleteObject(strval($request->calendarId))
        );
    }
}
