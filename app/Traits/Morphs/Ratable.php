<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Rate;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Ratable
{
    public function rates(): MorphMany
    {
        return $this->morphMany(Rate::class, 'ratable');
    }
}
