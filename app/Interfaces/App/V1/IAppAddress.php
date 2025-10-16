<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IAddress;

interface IAppAddress extends IAddress
{
    public function appAll($params = null): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
