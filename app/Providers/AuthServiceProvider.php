<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //

        // Gate::define('update-post', function (User $user, Equipment $equipment) {
        //     return $user->id === $equipment->user_id;
        // });

        // Gate::define('manage-equipments', function(User $user) {
        //     return $user->user_type === 'admin';
        // });

        Gate::define('manage-users', function ($user) {
            return $user->user_type === 'ผู้ดูแลระบบ';
        });

        Gate::define('view-branch-data', function ($user) {
            return $user->role === 'branch';
        });

        Gate::define('approve-budget', function ($user) {
            return $user->role === 'manager';
        });
    }
}
