<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquipmentsExport;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Filter
        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function($q) use ($search) {
                $q->where('number', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
            });
        }

        if ($request->filled('unit')) {
            $query->where('equipment_unit_id', $request->input('unit'));
        }

        if ($request->filled('storage')) {
            $query->where('location_id', $request->input('storage'));
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->input('user'));
        }

        $equipments = $query->with(['equipmentUnit', 'equipmentType', 'location', 'user', 'title'])->paginate(15);

        return view('page.equipments.show', [
            'equipments' => $equipments,
            'equipment_units' => Equipment_unit::all(),
            'locations' => Location::all(),
            'users' => User::all(),
        ]);
    }

    public function create()
{
    $equipment_units = Equipment_unit::all();
    $users = User::all();

    return view('page.equipments.add', compact('equipment_units', 'users'));
}

public function store(Request $request)
{
    $request->validate([
        'number' => 'required|unique:equipments',
        'name' => 'required',
        'equipment_unit_id' => 'required',
        'amount' => 'required|integer',
        'price' => 'required|numeric',
        // เพิ่ม validation ตามต้องการ
    ]);

    $data = $request->all();
    $data['total_price'] = $request->amount * $request->price;

    Equipment::create($data);

    return redirect()->route('equipment.index')->with('success', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
}

public function edit($id)
{
    $equipment = Equipment::findOrFail($id);
    $equipment_units = Equipment_unit::all();
    $users = User::all();

    return view('page.equipments.edit', compact('equipment', 'equipment_units', 'users'));
}

public function update(Request $request, $id)
{
    $equipment = Equipment::findOrFail($id);

    $request->validate([
        'number' => 'required|unique:equipments,number,' . $equipment->id,
        'name' => 'required',
        'equipment_unit_id' => 'required',
        'amount' => 'required|integer',
        'price' => 'required|numeric',
    ]);

    $data = $request->all();
    $data['total_price'] = $request->amount * $request->price;

    $equipment->update($data);

    return redirect()->route('equipment.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
}


    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'ลบข้อมูลสำเร็จ');
    }

    public function storeUnit(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:equipment_units',
    ]);

    // Create a new Equipment Unit
    $unit = new Equipment_unit();
    $unit->name = $request->input('name');
    $unit->save();

    // Redirect back to the form or page with success message
    return redirect()->route('equipment.add')->with('success', 'หน่วยนับใหม่ถูกเพิ่มแล้ว');
}


    // public function export()
    // {
    //     return Excel::download(new EquipmentsExport, 'equipments.xlsx');
    // }
}
