<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\UserParentController;
use App\Http\Requests\App\V1\AppUserParentCreateRequest;
use App\Http\Requests\App\V1\AppUserParentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppUserParentController extends UserParentController
{
    public function appGetAllUserParents(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function getUserParentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->userParentId)));
    }

    public function appCreateUserParent(AppUserParentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateUserParent(AppUserParentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->userParentId));
    }

    public function appDeleteUserParent(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->userParentId)));
    }
}
