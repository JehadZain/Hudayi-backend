<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppStudent;
use App\Interfaces\Common\V1\IStudent;
use App\Interfaces\Mobile\V1\IMobileStudent;
use App\Models\Users\User;

class StudentController extends Controller
{
    protected $model = User::class;

    public function __construct(IStudent $student, IAppStudent $appModel, IMobileStudent $mobModel)
    {
        $this->baseRepo = $student;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }

    //    public function getAllStudents():JsonResponse{
    //        $this->setData($this->baseRepo->all());
    //        return $this->response();
    //    }
    //
    //    public function getStudentById(int $id): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->byId($id));
    //        return $this->response();
    //    }
    //
    //    public function createStudent(AppStudentCreateRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->createObject([...$request->safe()]));
    //        return $this->response();
    //    }
    //
    //    public function editStudent(AppStudentEditRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->editById([...$request->safe()],$request->studentId));
    //        return $this->response();
    //    }
}
