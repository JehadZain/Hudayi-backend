<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IOrganizationAdmin;
use App\Models\OrganizationAdmin;
use App\Repositories\BaseRepository;

class OrganizationAdminRepository extends BaseRepository implements IOrganizationAdmin
{
    protected $model = OrganizationAdmin::class;

    public function __construct()
    {
        $this->build();
    }
}
