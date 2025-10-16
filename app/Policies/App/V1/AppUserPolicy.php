<?php

namespace App\Policies\App\V1;

use Illuminate\Auth\Access\HandlesAuthorization;

class AppUserPolicy
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
