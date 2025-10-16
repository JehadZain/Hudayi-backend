<?php

namespace App\Policies\Mobile\V1;

use Illuminate\Auth\Access\HandlesAuthorization;

class MobUserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
