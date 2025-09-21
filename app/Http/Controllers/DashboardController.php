<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Document;
use App\Models\Equipment_document;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        $documents = Document::all();

        // $totals = DB::table('equipment')
        //     ->selectRaw('
        //     SUM(status_found) as total_found,
        //     SUM(status_not_found) as total_not_found,
        //     SUM(status_broken) as total_broken,
        //     SUM(status_disposal) as total_disposal,
        //     SUM(status_transfer) as total_transfer
        // ')
        //     ->first();

        $totals = DB::table('equipment')
            ->selectRaw('
            SUM(amount) as total_found,
            SUM(amount) as total_not_found,
            SUM(amount) as total_broken,
            SUM(amount) as total_disposal,
            SUM(amount) as total_transfer
        ')
            ->first();

$arr = ['total_found' => '274','total_not_found' => '274',];

dd($totals,$arr);

        // กำหนดปี
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        $twoYearsAgo = $currentYear - 2;

        // สร้าง array เก็บผลรวมตามปี
        $totalsByYear = [];

        // foreach ([$twoYearsAgo, $lastYear, $currentYear] as $year) {
        //     $totalsByYear[$year] = DB::table('equipment')
        //         ->whereYear('created_at', $year) // สมมติใช้ created_at เป็นปีที่บันทึก
        //         ->selectRaw('
        //         SUM(status_found) as total_found,
        //         SUM(status_not_found) as total_not_found,
        //         SUM(status_broken) as total_broken,
        //         SUM(status_disposal) as total_disposal,
        //         SUM(status_transfer) as total_transfer
        //     ')
        //         ->first();
        // }

        foreach ([$twoYearsAgo, $lastYear, $currentYear] as $year) {
            $totalsByYear[$year] = Equipment_document::join('documents', 'equipment_documents.document_id', '=', 'documents.id')
                ->whereYear('documents.date', $year)
                ->whereNotNull('equipment_documents.equipment_id')
                ->whereNotNull('equipment_documents.document_id')
                ->selectRaw("
                    SUM(CASE 
                        WHEN documents.document_type = 'ยื่นแทงจำหน่ายครุภัณฑ์' 
                        THEN equipment_documents.amount 
                        ELSE 0 
                    END) as total_disposal_request,
                    SUM(CASE 
                        WHEN documents.document_type = 'แทงจำหน่ายครุภัณฑ์' 
                        THEN equipment_documents.amount 
                        ELSE 0 
                    END) as total_disposal,
                    SUM(CASE 
                        WHEN documents.document_type = 'โอนครุภัณฑ์' 
                        THEN equipment_documents.amount 
                        ELSE 0 
                    END) as total_transfer,
                    SUM(CASE 
                        WHEN documents.document_type = 'ไม่พบ' 
                        THEN equipment_documents.amount 
                        ELSE 0 
                    END) as total_not_found,
                    SUM(CASE 
                        WHEN documents.document_type = 'ชำรุด' 
                        THEN equipment_documents.amount 
                        ELSE 0 
                    END) as total_broken
                ")
                ->first();
        }


        // dd($totalsByYear[2023]);

        // ส่งไป Blade
        return view('dashboard', compact('equipments', 'totalsByYear', 'totals', 'currentYear', 'lastYear', 'twoYearsAgo'));
    }
}
