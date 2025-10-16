<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\QuizController;
use App\Http\Requests\App\V1\AppQuizCreateRequest;
use App\Http\Requests\App\V1\AppQuizEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppQuizController extends QuizController
{
    //repoFunctionModel
    public function appGetAllQuizzes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetQuizById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->quizId)));
    }

    public function appGetAllQuizzesByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllQuizzesByTeacherId(strval($request->teacherId)));
    }

    public function appCreateQuiz(AppQuizCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateQuiz(AppQuizEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->quizId));
    }

    public function appDeleteQuiz(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->quizId)));
    }
}
