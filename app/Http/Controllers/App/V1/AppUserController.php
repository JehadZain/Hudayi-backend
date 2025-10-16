<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\UserController;
use App\Http\Requests\App\V1\AppUserCreateRequest;
use App\Http\Requests\App\V1\AppUserEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppUserController extends UserController
{
    public function appGetAllUsers(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appAllUsersNotApproved(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAllUsersNotApproved($request));
    }

    public function getUserById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->userId)));
    }

    //    public function appCreateUser(AppUserCreateRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    //    }

    public function appCreateUser(AppUserCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request));
    }

    public function appUpdateUser(AppUserEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request, $request->userId));
    }

    public function appDeleteUser(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->userId)));
    }
}
