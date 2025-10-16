<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IQuiz;

interface IAppQuiz extends IQuiz
{
    public function appAll(): object;

    public function appGetAllQuizzesByTeacherId(string $id): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
