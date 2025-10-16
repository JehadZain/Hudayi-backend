<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\UserParentController;
use App\Http\Requests\Mobile\V1\MobileUserParentCreateRequest;
use App\Http\Requests\Mobile\V1\MobileUserParentEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileUserParentController extends UserParentController
{
    public function mobileGetAllUserParents(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function getUserParentById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->userParentId)));
    }

    public function mobileCreateUserParent(MobileUserParentCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateUserParent(MobileUserParentEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->userParentId));
    }

    public function mobileDeleteUserParent(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->userParentId)));
    }
}
