<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::enforceMorphMap([
            'teacher' => 'App\Models\Users\Teacher',
            'admin' => 'App\Models\Users\Admin',
            'parent' => 'App\Models\Users\UserParent',
            'student' => 'App\Models\Users\Student',
            'school' => 'App\Models\Properties\School',
            'mosque' => 'App\Models\Properties\Mosque',
        ]);
    }
}
