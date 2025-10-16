<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IPropertyAdmin;
use App\Models\PropertyAdmin;
use App\Repositories\BaseRepository;

class PropertyAdminRepository extends BaseRepository implements IPropertyAdmin
{
    protected $model = PropertyAdmin::class;

    public function __construct()
    {
        $this->build();
    }
}
