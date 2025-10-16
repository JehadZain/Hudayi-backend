<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Reference;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Referenceable
{
    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }
}
