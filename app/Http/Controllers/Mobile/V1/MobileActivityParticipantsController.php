<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ActivityParticipantsController;
use App\Http\Requests\Mobile\V1\MobileActivityParticipantsCreateRequest;
use App\Http\Requests\Mobile\V1\MobileActivityParticipantsEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileActivityParticipantsController extends ActivityParticipantsController
{
    //repoFunctionModel
    public function mobileGetAllActivityParticipants(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetActivityParticipantById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->activityParticipantId)));
    }

    public function mobileCreateActivityParticipant(MobileActivityParticipantsCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->safe()));
    }

    public function mobileUpdateActivityParticipant(MobileActivityParticipantsEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->safe(), $request->activityParticipantId));
    }

    public function mobileDeleteActivityParticipant(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->activityParticipantId)));
    }
}
