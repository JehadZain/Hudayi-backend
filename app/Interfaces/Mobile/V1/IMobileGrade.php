<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IGrade;

interface IMobileGrade extends IGrade
{
    public function mobileAll(): object;

    public function mobileById(string $id): object;

    public function mobileGetGradeStatistics(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileDeleteObject(string $id): object;
}
