<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ActivityParticipantsController;
use App\Http\Requests\App\V1\AppActivityParticipantsCreateRequest;
use App\Http\Requests\App\V1\AppActivityParticipantsEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppActivityParticipantsController extends ActivityParticipantsController
{
    //repoFunctionModel
    public function appGetAllActivityParticipants(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetActivityParticipantById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->activityParticipantId)));
    }

    public function appCreateActivityParticipant(AppActivityParticipantsCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->safe()));
    }

    public function appUpdateActivityParticipant(AppActivityParticipantsEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->safe(), $request->activityParticipantId));
    }

    public function appDeleteActivityParticipant(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->activityParticipantId)));
    }
}
