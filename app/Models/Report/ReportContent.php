<?php

namespace App\Models\Report;

use App\Traits\Report\BelongsToReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportContent extends Model
{
    use HasFactory, SoftDeletes, BelongsToReport;
}
