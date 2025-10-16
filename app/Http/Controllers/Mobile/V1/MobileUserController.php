<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\UserController;
use App\Http\Requests\Mobile\V1\MobileUserCreateRequest;
use App\Http\Requests\Mobile\V1\MobileUserEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileUserController extends UserController
{
    public function mobileGetAllUsers(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileAllUsersNotApproved(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAllUsersNotApproved($request));
    }

    public function getUserById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->userId)));
    }

    //    public function mobileCreateUser(MobileUserCreateRequest $request): JsonResponse
    //    {
    //        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    //    }

    public function mobileCreateUser(MobileUserCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request));
    }

    public function mobileUpdateUser(MobileUserEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request, $request->userId));
    }

    public function mobileDeleteUser(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->userId)));
    }
}
