<?php

namespace App\Http\Controllers;

use App\Models\Equipment; // ใช้โมเดล Equipment
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquipmentsExport;

class EquipmentExportController extends Controller
{
    // public function export()
    // {
    //     return Excel::download(new EquipmentsExport, 'equipments.xlsx');
    // }
}
