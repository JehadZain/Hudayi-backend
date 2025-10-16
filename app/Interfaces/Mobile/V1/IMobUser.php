<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IUser;

interface IMobUser extends IUser
{
    public function testUsersForMob();
}
