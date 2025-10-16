<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppTeacher;
use App\Interfaces\Common\V1\ITeacher;
use App\Interfaces\Mobile\V1\IMobileTeacher;
use App\Models\Users\Teacher;

class TeacherController extends Controller
{
    protected $model = Teacher::class;

    public function __construct(ITeacher $teacher, IAppTeacher $appModel, IMobileTeacher $mobModel)
    {
        $this->baseRepo = $teacher;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }

    //    public function getAllTeachers():JsonResponse{
    //        $this->setData($this->baseRepo->all());
    //        return $this->response();
    //    }
    //
    //    public function getTeacherById(int $id): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->byId($id));
    //        return $this->response();
    //    }
    //
    //    public function createTeacher(AppTeacherCreateRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->createObject([...$request->safe()]));
    //        return $this->response();
    //    }
    //
    //    public function editTeacher(AppTeacherEditRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->editById([...$request->safe()],$request->teacherId));
    //        return $this->response();
    //    }
}
