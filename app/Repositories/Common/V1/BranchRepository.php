<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IBranch;
use App\Models\Branch;
use App\Repositories\BaseRepository;

class BranchRepository extends BaseRepository implements IBranch
{
    protected $model = Branch::class;

    public function __construct()
    {
        $this->build();
    }
}
