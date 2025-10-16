<?php

namespace App\Models\Properties;

use App\Models\Infos\Grade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyGrade extends Model
{
    use HasFactory,SoftDeletes;

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
