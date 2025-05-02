<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Equipment_type;
use App\Models\Location;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquipmentsExport;
use Illuminate\Validation\Rule;

use App\Imports\UsersImport;
use App\Exports\UsersExport;

class EquipmentController extends Controller
{
    public function index()
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();

        $equipment_trash = Equipment::onlyTrashed()->get();

        return view('page.equipments.show', compact('equipment_trash','equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles'));
    }

    public function create()
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();
        return view('page.equipments.add', compact('equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles'));

        // return view('page.equipments.add');
    }

    public function store(Request $request)
    {

        // dd($request->all());


        $request->validate([
            'number' => 'required|string|max:255|unique:equipment,number',
            'name' => 'required|string|max:2000',
            'amount' => 'required|integer|max:9999999999',
            'price' => 'nullable|numeric|min:0|max:99999999.99',
            // 'total_price' => 'required|numeric|min:0|max:99999999.99',
            'status_found' => 'required|integer|max:9999999999',
            'status_not_found' => 'required|integer|max:9999999999',
            'status_broken' => 'required|integer|max:9999999999',
            'status_disposal' => 'required|integer|max:9999999999',
            'status_transfer' => 'required|integer|max:9999999999',
            'equipment_unit_id'  => 'required|integer|max:9999999999',
            'location_id'  => 'nullable|integer|max:9999999999',
            'equipment_type_id'  => 'nullable|integer|max:9999999999',
            'title_id'  => 'required|integer|max:9999999999',
            'user_id'  => 'nullable|integer|max:9999999999',
            'description'  => 'nullable|string|max:255'
        ]);

        $total_price = $request->price * $request->amount;
        // dd($request->all());

        Equipment::create([
            'number' =>  $request->number,
            'name' =>  $request->name,
            'amount' =>  $request->amount,
            'price' =>  $request->price,
            'total_price' =>  $total_price,
            'status_found' =>  $request->status_found,
            'status_not_found' =>  $request->status_not_found,
            'status_broken' =>  $request->status_broken,
            'status_disposal' =>  $request->status_disposal,
            'status_transfer' =>  $request->status_transfer,
            'equipment_unit_id'  =>  $request->equipment_unit_id,
            'location_id'  =>  $request->location_id,
            'equipment_type_id'  =>  $request->equipment_type_id,
            'title_id'  =>  $request->title_id,
            'user_id'  =>  $request->user_id,
            'description'  =>  $request->description
        ]);

        return redirect()->route('equipment.index')->with('success', 'เพิ่มครุภัณฑ์สำเร็จ');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();
        return view('page.equipments.edit', compact('equipment', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());
        $request->validate([
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment', 'number')->ignore($id)
            ],
            'name' => 'required|string|max:2000',
            'amount' => 'required|integer|max:9999999999',
            'price' => 'nullable|numeric|min:0|max:99999999.99',
            // 'total_price' => 'required|numeric|min:0|max:99999999.99',
            'status_found' => 'required|integer|max:9999999999',
            'status_not_found' => 'required|integer|max:9999999999',
            'status_broken' => 'required|integer|max:9999999999',
            'status_disposal' => 'required|integer|max:9999999999',
            'status_transfer' => 'required|integer|max:9999999999',
            'equipment_unit_id'  => 'required|integer|max:9999999999',
            'location_id'  => 'nullable|integer|max:9999999999',
            'equipment_type_id'  => 'nullable|integer|max:9999999999',
            'title_id'  => 'required|integer|max:9999999999',
            'user_id'  => 'nullable|integer|max:9999999999',
            'description'  => 'nullable|string|max:255'
        ]);

        $equipment = Equipment::findOrFail($id);


        $total_price = $request->price * $request->amount;

        $equipment->update(
            [
                'number' =>  $request->number,
                'name' =>  $request->name,
                'amount' =>  $request->amount,
                'price' =>  $request->price,
                'total_price' =>  $total_price,
                'status_found' =>  $request->status_found,
                'status_not_found' =>  $request->status_not_found,
                'status_broken' =>  $request->status_broken,
                'status_disposal' =>  $request->status_disposal,
                'status_transfer' =>  $request->status_transfer,
                'equipment_unit_id'  =>  $request->equipment_unit_id,
                'location_id'  =>  $request->location_id,
                'equipment_type_id'  =>  $request->equipment_type_id,
                'title_id'  =>  $request->title_id,
                'user_id'  =>  $request->user_id,
                'description'  =>  $request->description
            ]
        );

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

    public function getEquipmentTypes($title_id)
    {
        $types = Equipment_Type::where('title_id', $title_id)->get();
        return response()->json($types);
    }

    public function moveToTrash(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'ไม่ได้เลือกรายการ'], 400);
        }

        Equipment::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'ย้ายข้อมูลไปยังถังขยะสำเร็จ']);
    }

    public function restoreFromTrash(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'ไม่ได้เลือกรายการ'], 400);
        }

        Equipment::whereIn('id', $ids)->restore();

        return response()->json(['message' => 'ย้ายข้อมูลออกจากถังขยะสำเร็จ']);
    }


    public function export()
    {
        return Excel::download(new EquipmentsExport, 'equipments.xlsx');
    }
}
