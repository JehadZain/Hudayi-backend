<?php

namespace App\Traits\Morphs;

use App\Models\Morphs\Contact;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Contactable
{
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
}
