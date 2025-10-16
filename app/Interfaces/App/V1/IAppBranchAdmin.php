<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IBranchAdmin;

interface IAppBranchAdmin extends IBranchAdmin
{
    public function appAll(): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appTransferAdmin($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
