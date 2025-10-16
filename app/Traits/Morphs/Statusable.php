<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\ModelStatus;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Statusable
{
    public function statuses(): MorphMany
    {
        return $this->morphMany(ModelStatus::class, 'statusable');
    }
}
