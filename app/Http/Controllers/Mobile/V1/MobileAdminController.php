<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\AdminController;
use App\Http\Requests\Mobile\V1\MobileAdminCreateRequest;
use App\Http\Requests\Mobile\V1\MobileAdminEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileAdminController extends AdminController
{
    public function mobileGetAllAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->adminId)));
    }

    public function mobileGetAllUnassignedAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileGetAllUnassignedAdmins($request));
    }

    public function mobileCreateAdmin(MobileAdminCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileCreateBranchAdmin(MobileAdminCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateBranchAdmin([...$request->safe()]));
    }

    public function mobileUpdateAdmin(MobileAdminEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->adminId));
    }

    public function mobileDeleteAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->adminId)));
    }
}
