<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\ScoreController;
use App\Http\Requests\App\V1\AppScoreCreateRequest;
use App\Http\Requests\App\V1\AppScoreEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppScoreController extends ScoreController
{
    //repoFunctionModel
    public function appGetAllScores(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetScoreById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->scoreId)));
    }

    public function appCreateScore(AppScoreCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateScore(AppScoreEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->scoreId));
    }

    public function appDeleteScore(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->scoreId)));
    }
}
