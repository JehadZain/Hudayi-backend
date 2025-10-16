<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\BranchAdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileBranchAdminController extends BranchAdminController
{
    public function mobileGetAllBranchAdmins(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetBranchAdminById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->branchId)));
    }

    public function mobileTransferBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileTransferAdmin($request->all()));
    }

    public function mobileCreateBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject($request->all()));
    }

    public function mobileUpdateBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject($request->all(), $request->branchId));
    }

    public function mobileDeleteBranchAdmin(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->branchId)));
    }
}
