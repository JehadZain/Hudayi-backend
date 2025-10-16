<?php

namespace App\Http\Controllers\Common\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Common\V1\IUser;
use App\Interfaces\App\V1\IAppUser;
use App\Interfaces\Mobile\V1\IMobileUser;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;

class SelectableController extends Controller
{
    protected $model = User::class;

    public function __construct(IUser $user, IAppUser $appUser, IMobileUser $mobUser)
    {
        $this->baseRepo = $user;
        $this->appRepo = $appUser;
        $this->mobileRepo = $mobUser;
    }

    /**
     * Get selectable book subjects
     */
    public function selectBookSubjects(): JsonResponse
    {
        // TODO: Implement book subjects selection logic
        $this->setData([]);
        return $this->response();
    }

    /**
     * Get selectable grades
     */
    public function selectGrade(): JsonResponse
    {
        // TODO: Implement grades selection logic
        $this->setData([]);
        return $this->response();
    }

    /**
     * Get selectable admins
     */
    public function selectAdmin(): JsonResponse
    {
        // TODO: Implement admins selection logic
        $this->setData([]);
        return $this->response();
    }

    /**
     * Get selectable students
     */
    public function selectStudent(): JsonResponse
    {
        // TODO: Implement students selection logic
        $this->setData([]);
        return $this->response();
    }

    /**
     * Get selectable teachers
     */
    public function selectTeacher(): JsonResponse
    {
        // TODO: Implement teachers selection logic
        $this->setData([]);
        return $this->response();
    }
}
