<?php

namespace App\Repositories\Common\V1;

use App\Interfaces\Common\V1\ISelectable;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class SelectableRepository extends BaseRepository implements ISelectable
{
    // public function __construct()
    // {
    //     // $this->selectable($selectableClass);
    // }
    public function selectable(string $class): Builder
    {
        $this->model = $class;

        return $this->builder = $this->model::query();
    }
}
