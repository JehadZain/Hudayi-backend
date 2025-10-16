<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\SessionController;
use App\Http\Requests\Mobile\V1\MobileSessionCreateRequest;
use App\Http\Requests\Mobile\V1\MobileSessionEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSessionController extends SessionController
{
    //repoFunctionModel
    public function mobileGetAllSessions(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetSessionById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->sessionId)));
    }

    public function mobileCreateSession(MobileSessionCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateSession(MobileSessionEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->sessionId));
    }

    public function mobileDeleteSession(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->sessionId)));
    }
}
