<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Addressable
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
