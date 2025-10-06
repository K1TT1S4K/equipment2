<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Document;
use App\Models\Title;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        $documents = Document::all();

        $totals = DB::table('equipment')
            ->selectRaw('
            SUM(status_found) as total_found,
            SUM(status_not_found) as total_not_found,
            SUM(status_broken) as total_broken,
            SUM(status_disposal) as total_disposal,
            SUM(status_transfer) as total_transfer,
            SUM(amount) as total_amount
        ')
            ->first();

        // foreach ($equipments as $key => $equipment) {
        //     $totals["total_found"] += $equipment->amount - $equipment->getStatusBroken->sum('amount') - $equipment->getStatusNotFound->sum('amount') - $equipment->getStatusDisposal->sum('amount') - $equipment->getStatusTransfer->sum('amount');
        //     $totals["total_not_found"] += $equipment->getStatusNotFound->sum('amount');
        //     $totals["total_broken"] += $equipment->getStatusBroken->sum('amount');
        //     $totals["total_disposal"] += $equipment->getStatusDisposal->sum('amount');
        //     $totals["total_transfer"] += $equipment->getStatusTransfer->sum('amount');
        // }


        // $arr = ['total_found' => '274', 'total_not_found' => '274',];

        // dd($totals, $arr);

        // กำหนดปี
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        $twoYearsAgo = $currentYear - 2;

        // สร้าง array เก็บผลรวมตามปี
        $totalsByYear = [];

        $count = 2;

        foreach ([$twoYearsAgo, $lastYear, $currentYear] as $year) {
            $title = Title::latest('id')->skip($count)->first();
            $count--;
            if (!$title) continue;
            $totalsByYear[$year] = DB::table('equipment')
                ->where('title_id', $title->id) // สมมติใช้ created_at เป็นปีที่บันทึก
                ->selectRaw('
                SUM(amount) as total_amount,
                SUM(status_found) as total_found,
                SUM(status_not_found) as total_not_found,
                SUM(status_broken) as total_broken,
                SUM(status_disposal) as total_disposal,
                SUM(status_transfer) as total_transfer
            ')
                ->first();

            $totalsByYear[$year]->name = $title->name;
            // dd($totalsByYear[$year]);
        }

        // ส่งไป Blade
        return view('dashboard', compact('equipments', 'totalsByYear', 'totals', 'currentYear', 'lastYear', 'twoYearsAgo'));
    }
}
