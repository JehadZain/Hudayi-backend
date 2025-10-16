<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IPatient;

interface IMobilePatient extends IPatient
{
    public function mobileAll($params = null): object;

    public function mobileById(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileDeleteObject(string $id): object;
}
