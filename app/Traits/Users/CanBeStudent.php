<?php

namespace App\Traits\Users;

use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanBeStudent
{
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
