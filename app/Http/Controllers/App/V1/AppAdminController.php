<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\AdminController;
use App\Http\Requests\App\V1\AppAdminCreateRequest;
use App\Http\Requests\App\V1\AppAdminEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppAdminController extends AdminController
{
    public function appGetAllAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->adminId)));
    }

    public function appGetAllUnassignedAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllUnassignedAdmins($request));
    }

    public function appCreateAdmin(AppAdminCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appCreateBranchAdmin(AppAdminCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateBranchAdmin([...$request->safe()]));
    }

    public function appUpdateAdmin(AppAdminEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->adminId));
    }

    public function appDeleteAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->adminId)));
    }
}
