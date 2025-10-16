<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\MosqueController;
use App\Http\Requests\Mobile\V1\MobileMosqueCreateRequest;
use App\Http\Requests\Mobile\V1\MobileMosqueEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileMosqueController extends MosqueController
{
    public function mobileGetAllMosques(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetMosqueById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->mosqueId)));
    }

    public function mobileCreateMosque(MobileMosqueCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateMosque(MobileMosqueEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->mosqueId));
    }

    public function mobileDeleteMosque(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->mosqueId)));
    }
}
