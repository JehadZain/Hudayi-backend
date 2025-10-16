<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IPatient;

interface IAppPatient extends IPatient
{
    public function appAll($params = null): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
