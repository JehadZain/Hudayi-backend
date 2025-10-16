<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\QuranQuizController;
use App\Http\Requests\App\V1\AppQuranQuizCreateRequest;
use App\Http\Requests\App\V1\AppQuranQuizEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppQuranQuizController extends QuranQuizController
{
    //repoFunctionModel
    public function appGetAllQuranQuizzes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetQuranQuizById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->quranQuizId)));
    }

    public function appGetAllQuranQuizzesByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllQuranQuizzesByTeacherId(strval($request->teacherId)));
    }

    public function appCreateQuranQuiz(AppQuranQuizCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateQuranQuiz(AppQuranQuizEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->quranQuizId));
    }

    public function appDeleteQuranQuiz(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->quranQuizId)));
    }
}
