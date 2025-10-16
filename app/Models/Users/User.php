<?php

namespace App\Models\Users;

use App\Models\Infos\Activity;
use App\Models\Properties\Property;
use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Contactable;
use App\Traits\Morphs\Statusable;
use App\Traits\Users\CanBeAdmin;
use App\Traits\Users\CanBeStudent;
use App\Traits\Users\CanBeTeacher;
use App\Traits\Users\CanBeUserParent;
use App\Traits\Users\HasManyCertifications;
use App\Traits\Users\IsPatient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        CanBeAdmin,
        CanBeStudent,
        CanBeTeacher,
        CanBeUserParent,
        Addressable,
        Statusable,
        Contactable,
        IsPatient,
        HasManyCertifications,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'username',
        'identity_number',
        'phone',
        'gender',
        'birth_date',
        'birth_place',
        'father_name',
        'mother_name',
        'qr_code',
        'blood_type',
        'note',
        'current_address',
        'is_has_disease',
        'disease_name',
        'is_has_treatment',
        'treatment_name',
        'are_there_disease_in_family',
        'family_disease_note',
        'status',
        'image',
        'is_approved',
    ];

    public $quickSearchableArray = ['first_name', 'last_name', 'id', 'identity_number', 'username', 'phone'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
    ];

    public function Activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
