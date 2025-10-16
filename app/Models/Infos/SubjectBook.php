<?php

namespace App\Models\Infos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectBook extends Model
{
    use HasFactory,SoftDeletes;

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
