<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\BranchAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppBranchAdminController extends BranchAdminController
{
    public function appGetAllBranchAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetBranchAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->branchId)));
    }

    public function appTransferBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appTransferAdmin($request->all()));
    }

    public function appCreateBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject($request->all()));
    }

    public function appUpdateBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject($request->all(), $request->branchId));
    }

    public function appDeleteBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->branchId)));
    }
}
