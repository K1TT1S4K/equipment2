<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        activity()
            ->tap(function ($activity) {
                $activity->menu = 'ออกจากระบบ';
            })
            
            ->useLog(auth()->user()->full_name)
            ->log('บุคลากร');

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
