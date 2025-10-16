<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IOrganization;
use App\Models\Organization;
use App\Repositories\BaseRepository;

class OrganizationRepository extends BaseRepository implements IOrganization
{
    protected $model = Organization::class;

    public function __construct()
    {
        $this->build();
    }
}
