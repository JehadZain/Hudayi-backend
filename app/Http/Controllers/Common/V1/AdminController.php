<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppAdmin;
use App\Interfaces\Common\V1\IAdmin;
use App\Interfaces\Mobile\V1\IMobileAdmin;
use App\Models\Users\Admin;

class AdminController extends Controller
{
    protected $model = Admin::class;

    public function __construct(IAdmin $admin, IAppAdmin $appModel, IMobileAdmin $mobModel)
    {
        $this->baseRepo = $admin;
        $this->appRepo = $appModel;
        $this->mobileRepo = $mobModel;
    }

    //    public function getAllAdmins():JsonResponse{
    //        $this->setData($this->baseRepo->all());
    //        return $this->response();
    //    }
    //
    //    public function getAdminById(int $id): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->byId($id));
    //        return $this->response();
    //    }
    //
    //    public function createAdmin(AppAdminCreateRequest $request): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->createObject([...$request->safe()]));
    //        return $this->response();
    //    }
    //
    //    public function editAdmin(AppAdminEditRequest $request): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->editById([...$request->safe()], $request->adminId));
    //        return $this->response();
    //    }
}
