<?php

namespace App\Models\Infos;

use App\Models\Morphs\Rate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateType extends Model
{
    use HasFactory, SoftDeletes;

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
