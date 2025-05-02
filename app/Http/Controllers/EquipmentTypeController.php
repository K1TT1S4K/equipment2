<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment_type;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $types = Equipment_type::with('equipment_unit')->get();
        return response()->json($types);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'equipment_unit_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
    
        Equipment_type::create($validated);
        return response()->noContent();
    }

    public function update(Request $request, Equipment_type $equipmentType)
    {
        $equipmentType->update($request->validate(['name' => 'required']));
        $equipmentType->update($request->validate(['equipment_unit_id' => 'required']));
        $equipmentType->update($request->validate(['amount' => 'required']));
        $equipmentType->update($request->validate(['price' => 'required']));
        return response()->noContent();
    }

    public function destroy(Equipment_type $equipmentType)
    {
        $equipmentType->delete();
        return response()->noContent();
    }

    public function checkUsage(Equipment_type $equipmentType)
    {
        $inUse = $equipmentType->equipments()->exists(); // ตรวจว่ามีการใช้จริงหรือไม่
        return response()->json(['in_use' => $inUse]);
    }
}
