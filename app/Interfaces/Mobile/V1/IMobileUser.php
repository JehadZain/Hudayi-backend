<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IUser;

interface IMobileUser extends IUser
{
    public function mobileAll(): object;

    public function mobileAllUsersNotApproved(): object;

    public function mobileById(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileDeleteObject(string $id): object;
}
