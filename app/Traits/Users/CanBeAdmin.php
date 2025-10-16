<?php

namespace App\Traits\Users;

use App\Models\Users\Admin;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanBeAdmin
{
    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }
}
