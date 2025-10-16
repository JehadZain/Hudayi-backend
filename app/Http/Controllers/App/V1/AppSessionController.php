<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\SessionController;
use App\Http\Requests\App\V1\AppSessionCreateRequest;
use App\Http\Requests\App\V1\AppSessionEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppSessionController extends SessionController
{
    //repoFunctionModel
    public function appGetAllSessions(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetSessionById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->sessionId)));
    }

    public function appGetAllSessionByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllSessionByTeacherId(strval($request->teacherId)));
    }

    public function appCreateSession(AppSessionCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateSession(AppSessionEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->sessionId));
    }

    public function appDeleteSession(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->sessionId)));
    }
}
