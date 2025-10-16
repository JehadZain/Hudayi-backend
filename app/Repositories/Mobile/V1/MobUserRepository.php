<?php

namespace App\Repositories\Mobile\V1;

use App\Http\Resources\Mobile\V1\Users\MobUserResource;
use App\Interfaces\Mobile\V1\IMobUser;
use App\Repositories\Common\V1\UserRepository;

class MobUserRepository extends UserRepository implements IMobUser
{
    public function testUsersForMob()
    {
        return 'mobile users';
    }

    public function mobById(int $id): object
    {
        $obj = $this->model::whereId($id)->first();

        return new MobUserResource($obj);
    }
}
