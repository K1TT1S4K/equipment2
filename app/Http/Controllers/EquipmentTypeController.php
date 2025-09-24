<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment_type;
use Illuminate\Support\Facades\Log;


class EquipmentTypeController extends Controller
{
    public function index()
    {
        $types = Equipment_type::with('equipment_unit','title')->get();
        return response()->json($types);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title_id' => 'required|numeric',
            'equipment_unit_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'title_id' => 'required|numeric',
        ]);
    
        Equipment_type::create($validated);
        return response()->noContent();
    }

    public function update(Request $request, Equipment_type $type)

    {
        // \Log::info('Update Request Data:', $request->all());
        // \Log::info('Before Update:', $type->toArray());
        // dd($type);
            $validated = $request->validate([

                'name' => 'required',
                'title_id' => 'required|numeric',
                'equipment_unit_id' => 'required|numeric',
                'amount' => 'required',
                'price' => 'required',
        ]);
    
        $type->update($validated);
        return response()->noContent();
    }
    

    public function destroy(Equipment_type $type)
    {
        $type->delete();
        return response()->noContent();
    }

    public function checkUsage(Equipment_type $type)
    {
        $inUse = $type->equipments()->exists(); // ตรวจว่ามีการใช้จริงหรือไม่
        return response()->json(['in_use' => $inUse]);
    }
}
