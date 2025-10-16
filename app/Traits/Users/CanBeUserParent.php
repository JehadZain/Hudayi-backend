<?php

namespace App\Traits\Users;

use App\Models\Users\UserParent;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanBeUserParent
{
    public function userParents(): HasMany
    {
        return $this->hasMany(UserParent::class);
    }
}
