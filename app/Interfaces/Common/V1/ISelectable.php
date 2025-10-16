<?php

namespace App\Interfaces\Common\V1;

use App\Interfaces\IBase;
use Illuminate\Database\Eloquent\Builder;

interface ISelectable extends IBase
{
    public function selectable(string $class): Builder;
}
