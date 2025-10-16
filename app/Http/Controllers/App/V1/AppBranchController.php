<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\BranchController;
use App\Http\Requests\App\V1\AppBranchCreateRequest;
use App\Http\Requests\App\V1\AppBranchEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppBranchController extends BranchController
{
    public function appGetAllBranches(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetBranchById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->branchId)));
    }

    public function appCreateBranch(AppBranchCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateBranch(AppBranchEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->branchId));
    }

    public function appDeleteBranch(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->branchId)));
    }
}
