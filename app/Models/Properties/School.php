<?php

namespace App\Models\Properties;

use App\Traits\Morphs\Contactable;
use App\Traits\Morphs\Propertyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, Propertyable, Contactable, SoftDeletes;
}
