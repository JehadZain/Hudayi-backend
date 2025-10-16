<?php

namespace App\Traits\Report;

use App\Models\Morphs\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToReport
{
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
