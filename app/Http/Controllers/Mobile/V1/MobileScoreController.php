<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\ScoreController;
use App\Http\Requests\Mobile\V1\MobileScoreCreateRequest;
use App\Http\Requests\Mobile\V1\MobileScoreEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileScoreController extends ScoreController
{
    //repoFunctionModel
    public function mobileGetAllScores(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetScoreById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->scoreId)));
    }

    public function mobileCreateScore(MobileScoreCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateScore(MobileScoreEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->scoreId));
    }

    public function mobileDeleteScore(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->scoreId)));
    }
}
