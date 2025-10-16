<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\SubjectController;
use App\Http\Requests\App\V1\AppSubjectCreateRequest;
use App\Http\Requests\App\V1\AppSubjectEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppSubjectController extends SubjectController
{
    //repoFunctionModel
    public function appGetAllSubjects(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetSubjectById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->subjectId)));
    }

    public function appCreateSubject(AppSubjectCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateSubject(AppSubjectEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->subjectId));
    }

    public function appDeleteSubject(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->subjectId)));
    }
}
