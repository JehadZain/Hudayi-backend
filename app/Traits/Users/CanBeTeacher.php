<?php

namespace App\Traits\Users;

use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanBeTeacher
{
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
