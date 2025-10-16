<?php

namespace App\Models;

use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id'];

    public $quickSearchableArray = ['name', 'id'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branchAdmins(): HasMany
    {
        return $this->hasMany(BranchAdmin::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
