<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Score;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Scoreable
{
    public function scores(): MorphMany
    {
        return $this->morphMany(Score::class, 'scoreable');
    }
}
