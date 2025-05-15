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
        Gate::define('admin', function ($user) {
            return $user->user_type === 'ผู้ดูแลระบบ';
        });

        Gate::define('branch', function ($user) {
            return $user->user_type === 'เจ้าหน้าที่สาขา';
        });

        Gate::define('officer', function ($user) {
            return $user->user_type === 'ผู้ปฏิบัติงานบริหาร';
        });

        Gate::define('teacher', function ($user) {
            return $user->user_type === 'อาจารย์';
        });

        Gate::define('admin-or-branch', function ($user) {
            return $user->user_type === 'เจ้าหน้าที่สาขา' || $user->user_type === 'ผู้ดูแลระบบ';
        });

        Gate::define('admin-or-branch-or-officer', function ($user) {
            return $user->user_type === 'เจ้าหน้าที่สาขา' || $user->user_type === 'ผู้ดูแลระบบ' || $user->user_type === 'ผู้ปฏิบัติงานบริหาร';
        });
    }
}
