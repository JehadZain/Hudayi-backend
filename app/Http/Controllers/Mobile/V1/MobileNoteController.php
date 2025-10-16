<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Common\V1\NoteController;
use App\Http\Requests\Mobile\V1\MobileNoteCreateRequest;
use App\Http\Requests\Mobile\V1\MobileNoteEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileNoteController extends NoteController
{
    //repoFunctionModel
    public function mobileGetAllNotes(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileAll($request));
    }

    public function mobileGetNoteById(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileById(strval($request->noteId)));
    }

    public function mobileCreateNote(MobileNoteCreateRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileCreateObject([...$request->safe()]));
    }

    public function mobileUpdateNote(MobileNoteEditRequest $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileUpdateObject([...$request->safe()], $request->noteId));
    }

    public function mobileDeleteNote(Request $request): JsonResponse
    {
        return $this->response($request, $this->mobileRepo->mobileDeleteObject(strval($request->noteId)));
    }
}
