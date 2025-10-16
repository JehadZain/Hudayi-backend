<?php

namespace App\Traits\Users;

use App\Models\Infos\Certification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyCertifications
{
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }
}
