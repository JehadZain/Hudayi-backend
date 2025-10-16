<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\ITeacher;

interface IAppTeacher extends ITeacher
{
    public function appAll(): object;

    public function appGetAllOrgTeacher(): object;

    public function appById(string $id): object;

    public function teacherStatistics(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appTransferTeacher($object, $teacherId): object;

    public function appDeleteObject(string $id): object;
}
