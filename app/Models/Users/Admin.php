<?php

namespace App\Models\Users;

use App\Models\Branch;
use App\Models\BranchAdmin;
use App\Models\Infos\JobTitle;
use App\Models\Morphs\Rate;
use App\Models\Morphs\Report;
use App\Models\Note;
use App\Models\Organization;
use App\Models\OrganizationAdmin;
use App\Models\Properties\Property;
use App\Models\PropertyAdmin;
use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Contactable;
use App\Traits\Morphs\Referenceable;
use App\Traits\Morphs\Scoreable;
use App\Traits\Morphs\Statusable;
use App\Traits\Users\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory,
        BelongsToUser,
        Referenceable,
        Scoreable,
        SoftDeletes,
        Statusable,
        Addressable,
        Contactable;

    protected $fillable = [
        //        "job_title_id",
        'wives_count',
        'children_count',
        'property_id',
        'marital_status',

    ];

    //    public $quickSearchableArray  = ['first_name','last_name','id','identity_number','username','phone'];

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)->wherePivot('property_admin');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)->wherePivot('branch_admin');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->wherePivot('organization_admin');
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function scopeAppAdminWithUser($query)
    {
        return $query->select(
            'id',
            'user_id',
            'job_title_id',
            'wives_count',
            'children_count',
            'property_id',
            'marital_status'

        )->with('user:id,property_id,email,first_name,last_name,username,identity_number,phone,gender,birth_date,birth_place,father_name,mother_name,qr_code,blood_type,note,current_address,is_has_disease,disease_name,is_has_treatment,treatment_name,are_there_disease_in_family,family_disease_note,status,image')
//            ->with('jobTitle')
//            ->with('user.property.branch')
            ->with('user.certifications')
            ->with('rates.teacher.user:id,first_name,last_name,image')
            ->with(['rates' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }]);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function organizationAdmins(): HasMany
    {
        return $this->hasMany(OrganizationAdmin::class);
    }

    public function branchAdmins(): HasMany
    {
        return $this->hasMany(BranchAdmin::class);
    }

    public function propertyAdmins(): HasMany
    {
        return $this->hasMany(PropertyAdmin::class);
    }
}
