<?php

namespace App\Models\Users;

use App\Traits\Morphs\Addressable;
use App\Traits\Morphs\Contactable;
use App\Traits\Users\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserParent extends Model
{
    use HasFactory,
        BelongsToUser,
        SoftDeletes,
        Addressable,
        Contactable;

    public function scopeAppParentWithUser($query)
    {
        return $query->select(
            'id',
            'user_id',
        )->with('user');
    }
}
