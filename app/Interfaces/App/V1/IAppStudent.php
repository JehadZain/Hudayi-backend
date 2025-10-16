<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IStudent;

interface IAppStudent extends IStudent
{
    public function appAll(): object;

    public function appGetAllOrgStudents(): object;

    public function appById(string $id): object;

    public function studentStatistics(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appTransferStudent($object, $studentId): object;

    public function appDeleteObject(string $id): object;
}
