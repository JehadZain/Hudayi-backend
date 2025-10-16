<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\IUser;
use App\Models\Users\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUser
{
    protected $model = User::class;

    public function __construct()
    {
        $this->build();
    }
}
