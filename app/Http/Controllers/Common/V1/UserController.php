<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\App\V1\IAppUser;
use App\Interfaces\Common\V1\IUser;
use App\Interfaces\Mobile\V1\IMobileUser;
use App\Models\Users\User;

class UserController extends Controller
{
    protected $model = User::class;

    public function __construct(IUser $user, IAppUser $appUser, IMobileUser $mobUser)
    {
        $this->baseRepo = $user;
        $this->appRepo = $appUser;
        $this->mobileRepo = $mobUser;
    }

    //    public function getAllUsers():JsonResponse{
    //        $this->setData($this->baseRepo->all());
    //        return $this->response();
    //    }
    //
    //    public function getUserById(int $id): JsonResponse
    //    {
    //        $this->setData($this->baseRepo->byId($id));
    //        return $this->response();
    //    }
    //
    //    public function createUser(AppUserCreateRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->createObject([...$request->safe()]));
    //        return $this->response();
    //    }
    //
    //    public function editUser(AppUserEditRequest $request):JsonResponse{
    //        $this->setData($this->baseRepo->editById([...$request->safe()],$request->userId));
    //        return $this->response();
    //    }
}
