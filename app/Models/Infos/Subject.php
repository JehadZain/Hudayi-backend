<?php

namespace App\Models\Infos;

use App\Models\Properties\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    //    public function property(): BelongsTo
    //    {
    //        return $this->belongsTo(Property::class);
    //    }

    //    public function books(): HasMany
    //    {
    //        return $this->hasMany(Book::class);
    //    }

    //    public function sessions(): HasMany
    //    {
    //        return $this->hasMany(Session::class);
    //    }
}
