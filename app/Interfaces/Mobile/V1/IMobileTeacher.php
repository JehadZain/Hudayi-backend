<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\ITeacher;

interface IMobileTeacher extends ITeacher
{
    public function mobileAll(): object;

    public function mobileGetAllOrgTeacher(): object;

    public function mobileById(string $id): object;

    public function teacherStatistics(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileTransferTeacher($object, $teacherId): object;

    public function mobileDeleteObject(string $id): object;
}
