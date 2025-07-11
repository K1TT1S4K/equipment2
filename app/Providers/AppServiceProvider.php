<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Equipment_unit; // หรือโมเดลที่คุณใช้
use App\Models\Equipment_type;
use App\Models\Title;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Gate::define('admin', function (User $user): bool {
        //     return (bool) $user->is_admin;
        // });


      
        View::composer('components.layouts.app', function ($view) {
            $units = Equipment_unit::all(); // ดึงข้อมูลหน่วยอุปกรณ์
            $equipment_types = Equipment_type::all(); // ดึงข้อมูลประเภทอุปกรณ์
            $titles = Title::all();
        
            $view->with([
                'units' => $units,
                'equipment_types' => $equipment_types,
                'titles' => $titles
            ]);
        });
        
        
    }
}
