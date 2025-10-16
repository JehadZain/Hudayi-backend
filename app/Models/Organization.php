<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function organizationAdmins(): HasMany
    {
        return $this->hasMany(OrganizationAdmin::class);
    }
}
