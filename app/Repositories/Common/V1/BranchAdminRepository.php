<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IBranchAdmin;
use App\Models\BranchAdmin;
use App\Repositories\BaseRepository;

class BranchAdminRepository extends BaseRepository implements IBranchAdmin
{
    protected $model = BranchAdmin::class;

    public function __construct()
    {
        $this->build();
    }
}
