<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IAdmin;
use App\Models\BranchAdmin;
use App\Models\OrganizationAdmin;
use App\Models\PropertyAdmin;
use App\Models\Users\Admin;
use App\Models\Users\User;
use App\Repositories\BaseRepository;

class AdminRepository extends BaseRepository implements IAdmin
{
    protected $model = Admin::class;

    protected $associate = User::class;

    protected $orgAdminAssociate = OrganizationAdmin::class;

    protected $branchAdminAssociate = BranchAdmin::class;

    protected $propertyAdminAssociate = PropertyAdmin::class;

    public function __construct()
    {
        $this->build();
    }
}
