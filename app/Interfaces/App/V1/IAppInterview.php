<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IInterview;

interface IAppInterview extends IInterview
{
    public function appAll(): object;

    public function appGetAllInterviewsByTeacherId(string $id): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
