<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();

        $totals = DB::table('equipment')
            ->selectRaw('
            SUM(status_found) as total_found,
            SUM(status_not_found) as total_not_found,
            SUM(status_broken) as total_broken,
            SUM(status_disposal) as total_disposal,
            SUM(status_transfer) as total_transfer
        ')
            ->first();

        return view('dashboard', compact('equipments', 'totals'));
    }
}
