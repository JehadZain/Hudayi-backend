<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IUser;

interface IAppUser extends IUser
{
    public function appAll(): object;

    public function appAllUsersNotApproved(): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
