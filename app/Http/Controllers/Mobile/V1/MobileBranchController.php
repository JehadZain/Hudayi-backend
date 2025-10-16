<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\BranchController;
use App\Http\Requests\Mobile\V1\MobileBranchCreateRequest;
use App\Http\Requests\Mobile\V1\MobileBranchEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileBranchController extends BranchController
{
    public function mobileGetAllBranches(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetBranchById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->branchId)));
    }

    public function mobileCreateBranch(MobileBranchCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateBranch(MobileBranchEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->branchId));
    }

    public function mobileDeleteBranch(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->branchId)));
    }
}
