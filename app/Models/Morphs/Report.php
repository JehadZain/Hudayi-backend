<?php

namespace App\Models\Morphs;

use App\Models\Report\ReportContent;
use App\Models\Report\ReportReviewer;
use App\Models\Report\ReportType;
use App\Models\Users\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reportType(): BelongsTo
    {
        return $this->belongsTo(ReportType::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function reportReviewers(): HasMany
    {
        return $this->hasMany(ReportReviewer::class);
    }

    public function reportContent(): HasOne
    {
        return $this->hasOne(ReportContent::class);
    }
}
