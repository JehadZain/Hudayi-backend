<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IParent;
use App\Interfaces\App\V1\IAppParent;
use App\Interfaces\Mobile\V1\IMobileParent;
use App\Models\Users\UserParent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserParentController extends Controller
{
    protected $model = UserParent::class;
    protected $baseRepo;
    protected $appRepo;
    protected $mobileRepo;

    public function __construct(IParent $userParent, IAppParent $appUserParent, IMobileParent $mobileUserParent)
    {
        $this->baseRepo = $userParent;
        $this->appRepo = $appUserParent;
        $this->mobileRepo = $mobileUserParent;
    }

}
