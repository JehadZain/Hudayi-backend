<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IParent;
use App\Models\Users\User;
use App\Models\Users\UserParent;
use App\Repositories\BaseRepository;

class UserParentRepository extends BaseRepository implements IParent
{
    protected $model = UserParent::class;

    protected $associate = User::class;

    public function __construct()
    {
        $this->build();
    }
}
