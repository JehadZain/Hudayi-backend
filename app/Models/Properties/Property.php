<?php

namespace App\Models\Properties;

use App\Models\Branch;
use App\Models\Infos\Grade;
use App\Models\PropertyAdmin;
use App\Models\Users\Admin;
use App\Models\Users\User;
use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Contactable;
use App\Traits\Morphs\Ratable;
use App\Traits\Users\CanBeAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory,
        Contactable,
        Addressable,
        CanBeAdmin,
        SoftDeletes,
        Ratable;

    protected $fillable = [
        'id',
        'name',
        'capacity',
        'property_type',
        'branch_id',
        'description',
        'email',
        'phone',
        'whatsapp',
        'facebook',
        'instagram',
        'location',
    ];

    public $quickSearchableArray = ['name', 'id'];

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class)->where('is_approved', true);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function propertyAdmins(): HasMany
    {
        return $this->hasMany(PropertyAdmin::class);
    }
}
