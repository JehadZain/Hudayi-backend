<?php

namespace App\Models\Report;

use App\Models\Morphs\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportType extends Model
{
    use HasFactory, SoftDeletes;

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
