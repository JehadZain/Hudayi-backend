<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IReportReview;

interface IAppReportReview extends IReportReview
{
    public function appAll(): object;

    public function appById(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
