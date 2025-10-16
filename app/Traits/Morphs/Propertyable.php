<?php

namespace App\Traits\Morphs;

use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Propertyable
{
    public function property(): MorphOne
    {
        return $this->morphOne(Property::class, 'propertyable');
    }
}
