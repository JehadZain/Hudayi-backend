<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IStudent;

interface IMobileStudent extends IStudent
{
    public function mobileAll(): object;

    public function mobileGetAllOrgStudents(): object;

    public function mobileById(string $id): object;

    public function studentStatistics(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileTransferStudent($object, $studentId): object;

    public function mobileDeleteObject(string $id): object;
}
