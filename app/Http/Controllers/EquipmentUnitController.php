<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment_unit;
use Illuminate\Support\Facades\View;

class EquipmentUnitController extends Controller
{


    public function index()
    {
        return response()->json(Equipment_unit::all());
    }

    public function store(Request $request)
    {
        Equipment_unit::create($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function update(Request $request, Equipment_unit $equipment_unit)
    {
        $equipment_unit->update($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function destroy(Equipment_unit $equipment_unit)
    {
        $equipment_unit->delete();
        return response()->noContent();
    }

    public function checkUsage(Equipment_unit $equipment_unit)
    {
        $inUse = $equipment_unit->equipments()->exists(); // ตรวจว่ามีการใช้จริงหรือไม่
        return response()->json(['in_use' => $inUse]);
    }
}
