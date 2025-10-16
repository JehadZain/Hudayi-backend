<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\CalendarController;
use App\Http\Requests\Mobile\V1\MobileCalendarCreateRequest;
use App\Http\Requests\Mobile\V1\MobileCalendarEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCalendarController extends CalendarController
{
    public function mobileGetAllCalendars(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileAll($request)
        );
    }

    public function mobileGetCalendarById(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileById(strval($request->calendarId))
        );
    }

    public function mobileCreateCalendar(MobileCalendarCreateRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileCreateObject([...$request->safe()])
        );
    }

    public function mobileUpdateCalendar(MobileCalendarEditRequest $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->calendarId)
        );
    }

    public function mobileDeleteCalendar(Request $request): JsonResponse
    {
        return $this->response(
            $request,
            $this->mobileRepo->mobileDeleteObject(strval($request->calendarId))
        );
    }
}
