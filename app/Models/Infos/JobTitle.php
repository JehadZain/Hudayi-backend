<?php

namespace App\Models\Infos;

use App\Models\Users\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitle extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeAppAdminJobTitle($query)
    {
        return $query->select(
            'id',
            'name',
            'description',
        );
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }
}
