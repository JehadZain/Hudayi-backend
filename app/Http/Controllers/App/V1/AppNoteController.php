<?php

namespace App\Http\Controllers\App\V1;

use App\Http\Controllers\Common\V1\NoteController;
use App\Http\Requests\App\V1\AppNoteCreateRequest;
use App\Http\Requests\App\V1\AppNoteEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppNoteController extends NoteController
{
    //repoFunctionModel
    public function appGetAllNotes(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appAll($request));
    }

    public function appGetNoteById(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appById(strval($request->noteId)));
    }

    public function appGetAllNotesByTeacherId(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appGetAllNotesByTeacherId(strval($request->teacherId)));
    }

    public function appCreateNote(AppNoteCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appCreateObject([...$request->safe()]));
    }

    public function appUpdateNote(AppNoteEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appUpdateObject([...$request->safe()], $request->noteId));
    }

    public function appDeleteNote(Request $request): JsonResponse
    {
        return $this->response($request, $this->appRepo->appDeleteObject(strval($request->noteId)));
    }
}
