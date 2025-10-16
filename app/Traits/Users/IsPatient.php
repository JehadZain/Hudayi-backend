<?php

namespace App\Traits\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait IsPatient
{
    public function patient(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
