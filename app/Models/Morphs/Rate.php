<?php

namespace App\Models\Morphs;

use App\Models\Infos\RateType;
use App\Models\Users\Admin;
use App\Models\Users\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory, SoftDeletes;

//    public function ratable(): MorphTo
//    {
//        return $this->morphTo();
//    }
//
//    public function rateType(): BelongsTo
//    {
//        return $this->belongsTo(RateType::class);
//    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
