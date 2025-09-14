<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Equipment_type;
use App\Models\Equipment_log;
use App\Models\Location;
use App\Models\Prefix;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquipmentsExport;
use Illuminate\Validation\Rule;

use App\Models\Document as ModelsDocument;
use App\Models\Equipment_document;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Arr;


class EquipmentController extends Controller
{
    // หน้าแสดงข้อมูล
    public function index(Request $request)
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $locations = Location::all();
        $titles = Title::all();
        $logs = Equipment_log::all();
        $equipment_trash = Equipment::onlyTrashed()->get();

        $search = null;
        if (!empty($request['query'])) $search = $request->input('query');
        $title = $request->input('title_filter'); // ค้นหาจากประเภทหัวข้อ
        $unit = $request->input('unit_filter'); //ค้นหาจากประเภทหน่วยนับ
        $location = $request->input('location_filter');
        $user = $request->input('user_filter');
        $equipments = Equipment::when($search, function ($query, $search) {
            if ($search != 'all')
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")
                        ->orWhere('total_price', 'like', "%{$search}%")
                        ->orWhere('status_found', 'like', "%{$search}%")
                        ->orWhere('status_not_found', 'like', "%{$search}%")
                        ->orWhere('status_broken', 'like', "%{$search}%")
                        ->orWhere('status_disposal', 'like', "%{$search}%")
                        ->orWhere('status_transfer', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%")
                        ->orWhere('updated_at', 'like', "%{$search}%");
                });
        })
            ->when($title, function ($query, $title) {
                $query->where('title_id', $title); // กรองตามหัวข้อ
            })
            ->when($unit, function ($query, $unit) {
                if ($unit != 'all') $query->where('equipment_unit_id', $unit); // กรองตามหน่วยนับ
            })
            ->when($location, function ($query, $location) {
                if ($location != 'all') $query->where('location_id', $location); // กรองตามที่อยู่
            })
            ->when($user, function ($query, $user) {
                if ($user != 'all') $query->where('user_id', $user); // กรองตามผู้ดูแล
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $equipments->appends($request->all());

        return view('page.equipments.show', compact('equipment_trash', 'equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles', 'logs'));
    }

    // หน้ากู้คืนข้อมูล
    public function trash(Request $request)
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $locations = Location::all();
        $titles = Title::all();
        $logs = Equipment_log::all();
        $equipment_trash = Equipment::onlyTrashed()->get();

        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $title = $request->input('title_filter'); // ค้นหาจากประเภทหัวข้อ
        $unit = $request->input('unit_filter'); //ค้นหาจากประเภทหน่วยนับ
        $location = $request->input('location_filter');
        $user = $request->input('user_filter');

        $equipments = Equipment::when($search, function ($query, $search) {
            return $query->where('number', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('total_price', 'like', "%{$search}%")
                ->orWhere('status_found', 'like', "%{$search}%")
                ->orWhere('status_not_found', 'like', "%{$search}%")
                ->orWhere('status_broken', 'like', "%{$search}%")
                ->orWhere('status_disposal', 'like', "%{$search}%")
                ->orWhere('status_transfer', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('updated_at', 'like', "%{$search}%");
        })
            ->when($title, function ($query, $title) {
                $query->where('title_id', $title); // กรองตามหัวข้อ
            })
            ->when($unit, function ($query, $unit) {
                if ($unit != 'all') $query->where('equipment_unit_id', $unit); // กรองตามหน่วยนับ
            })
            ->when($location, function ($query, $location) {
                if ($location != 'all') $query->where('location_id', $location); // กรองตามที่อยู่
            })
            ->when($user, function ($query, $user) {
                if ($user != 'all') $query->where('user_id', $user); // กรองตามผู้ดูแล
            })
            ->onlyTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $equipments->appends($request->all());

        return view('page.equipments.trash', compact('equipment_trash', 'equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles', 'logs'));
    }

    // หน้าสร้างข้อมูล
    public function create()
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();

        return view('page.equipments.add', compact('equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles'));
    }

    // หน้าแก้ไขข้อมูล
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();
        $logs = Equipment_log::all();
        $equipment_documents = Equipment_document::all();
        $documents = ModelsDocument::all();

        return view('page.equipments.edit', compact('equipments', 'equipment_documents', 'equipment', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles', 'logs')) . $id;
    }

    // ฟังก์ชันสร้างข้อมูล
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:equipment,number',
            'name' => 'required|string|max:2000',
            'amount' => 'required|integer|max:9999999999',
            'price' => 'nullable|numeric|min:0|max:99999999.99',
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

        $equipment = Equipment::latest('id')->first();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'เพิ่มข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($equipment)
            ->withProperties(array_merge($equipment->only([
                'number',
                'name',
                'amount',
                'price',
                'total_price',
                'status_found',
                'status_not_found',
                'status_broken',
                'status_disposal',
                'status_transfer',
                'description'
            ]), [
                'unit' => optional($equipment->equipmentUnit)->name,
                'location' => optional($equipment->location)->name,
                'type' => optional($equipment->equipmentType)->name,
                'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                'user' => optional($equipment->user)->full_name
            ]))
            ->log('ครุภัณฑ์');

        return redirect($request->input('redirect_to', route('equipment.index')))->with('success', 'เพิ่มครุภัณฑ์สำเร็จ');
    }

    // ฟังก์ชันแก้ไขข้อมุล
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $locationNameMap = Location::pluck('name', 'id')->toArray();
        $unitNameMap = Equipment_unit::pluck('name', 'id')->toArray();
        $typeNameMap = Equipment_type::pluck('name', 'id')->toArray();
        $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
        $prefixNameMap = Prefix::pluck('name', 'id');
        $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
        $userFirstnameMap = User::pluck('firstname', 'id')->toArray();
        $userLastnameMap = User::pluck('lastname', 'id')->toArray();
        $titleGroupMap = Title::pluck('group', 'id')->toArray();
        $titleNameMap = Title::pluck('name', 'id')->toArray();

        $changes = [];

        // กำหนดฟิลด์ที่ต้องการตรวจสอบการเปลี่ยนแปลง
        $fieldsToCheck = [
            'number' => 'หมายเลขครุภัณฑ์',
            'name' => 'ชื่อครุภัณฑ์',
            'equipment_unit_id' => 'หน่วยนับ',
            'amount' => 'จำนวน',
            'price' => 'ราคา',
            'status_found' => 'พบ',
            'status_not_found' => 'ไม่พบ',
            'status_broken' => 'ชำรุด',
            'status_disposal' => 'จำหน่าย',
            'status_transfer' => 'โอน',
            'title_id' => 'หัวข้อ',
            'equipment_type_id' => 'ประเภท',
            'user_id' => 'ผู้ดูแล',
            'location_id' => 'ที่อยู่',
            'description' => 'คำอธิบาย',
        ];

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

        foreach ($fieldsToCheck as $field => $label) {
            $oldValue = $equipment->$field;
            $newValue = $request->$field;

            if ((string)$oldValue !== (string)$newValue) {
                // กรณีเป็น relational (เช่น ID ต้องแสดงชื่อแทน)
                if (in_array($field, ['equipment_unit_id', 'title_id', 'equipment_type_id', 'user_id', 'location_id'])) {
                    switch ($field) {
                        case "equipment_unit_id":
                            $oldName = $unitNameMap[$equipment->$field] ?? '-';
                            $newName = $unitNameMap[$request->$field] ?? '-';
                            break;
                        case "title_id":
                            $oldName = ($titleGroupMap[$equipment->$field] ?? '-') . " - " . ($titleNameMap[$equipment->$field] ?? '-');
                            $newName = ($titleGroupMap[$request->$field] ?? '-') . " - " . ($titleNameMap[$request->$field] ?? '-');
                            break;
                        case "equipment_type_id":
                            $oldName = $typeNameMap[$equipment->$field] ?? '-';
                            $newName = $typeNameMap[$request->$field] ?? '-';
                            break;
                        case "user_id":
                            $oldName = ($prefixNameMap[$userPrefixMap[$equipment->$field] ?? 0] ?? '-') . ($userFirstnameMap[$equipment->$field] ?? '-') . " " . ($userLastnameMap[$equipment->$field] ?? '-');
                            $newName = ($prefixNameMap[$userPrefixMap[$request->$field] ?? 0] ?? '-') . ($userFirstnameMap[$request->$field] ?? '-') . " " . ($userLastnameMap[$request->$field] ?? '-');
                            break;
                        case "location_id":
                            $oldName = $locationNameMap[$equipment->$field] ?? '-';
                            $newName = $locationNameMap[$request->$field] ?? '-';
                            break;
                    }
                    $changes[] = "{$label}: {$oldName} |->| {$newName}";
                } else {
                    $changes[] = "{$label}: {$oldValue} |->| {$newValue}";
                }
            }
        }

        $oldValues = $equipment->toArray(); // คัดลอกเป็น array แยกออกมา

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

        $newValues = $equipment->toArray();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'แก้ไขข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($equipment)
            ->withProperties([
                'ข้อมูลก่อนแก้ไข' => array_merge(
                    Arr::only($oldValues, [
                        'number',
                        'name',
                        'amount',
                        'price',
                        'total_price',
                        'status_found',
                        'status_not_found',
                        'status_broken',
                        'status_disposal',
                        'status_transfer',
                        'description'
                    ]),
                    [
                        'unit' => optional($equipment->equipmentUnit)->name,
                        'location' => optional($equipment->location)->name,
                        'type' => optional($equipment->equipmentType)->name,
                        'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                        'user' => optional($equipment->user)->full_name
                    ]
                ),
                'ข้อมูลหลังแก้ไข' => array_merge(
                    Arr::only($newValues, [
                        'number',
                        'name',
                        'amount',
                        'price',
                        'total_price',
                        'status_found',
                        'status_not_found',
                        'status_broken',
                        'status_disposal',
                        'status_transfer',
                        'description'
                    ]),
                    [
                        'unit' => optional($equipment->equipmentUnit)->name,
                        'location' => optional($equipment->location)->name,
                        'type' => optional($equipment->equipmentType)->name,
                        'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                        'user' => optional($equipment->user)->full_name
                    ]
                )
            ])
            ->log('ครุภัณฑ์');

        // ตัวอย่าง: log หรือแสดงใน view ก็ได้
        if (count($changes) > 0) {
            $message = implode("\n", $changes);

            // ✳️ บันทึกลง table equipment_logs
            Equipment_Log::create([
                'equipment_id' => $equipment->id,
                'user_id' => auth()->id(), // ผู้ที่ทำการเปลี่ยนแปลง
                'action' => $message,
            ]);
        }
        return redirect($request->input('redirect_to', route('equipment.index')))->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    /// ฟังก์ชัน softdelete ข้อมูลครุภัณฑ์
    public function deleteSelected(Request $request)
    {
        $equipmentIds = $request->input('selected_equipments');
        if ($equipmentIds) {
            $equipments = Equipment::whereIn('id', $equipmentIds)->get();

            LogBatch::startBatch();
            foreach ($equipments as $equipment) {
                activity()
                    ->tap(function ($activity) {
                        $activity->menu = 'ลบข้อมูลแบบซอฟต์';
                    })
                    ->useLog(auth()->user()->full_name)
                    ->performedOn($equipment)
                    ->withProperties(array_merge($equipment->only([
                        'number',
                        'name',
                        'amount',
                        'price',
                        'total_price',
                        'status_found',
                        'status_not_found',
                        'status_broken',
                        'status_disposal',
                        'status_transfer',
                        'description'
                    ]), [
                        'unit' => optional($equipment->equipmentUnit)->name,
                        'location' => optional($equipment->location)->name,
                        'type' => optional($equipment->equipmentType)->name,
                        'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                        'user' => optional($equipment->user)->full_name
                    ]))
                    ->log('ครุภัณฑ์');
            }
            LogBatch::endBatch();

            Equipment::whereIn('id', $equipmentIds)->delete(); // <-- ใช้ SoftDelete ถ้าเปิดใช้งาน
            return redirect($request->input('redirect_to', route('equipment.index')))->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
        }
        return redirect($request->input('redirect_to', route('equipment.index')))->with('error', 'กรุณาเลือกเอกสาร');
    }

    /// ฟังก์ชันกู้ข้อมูลครุภัณฑ์
    public function restoreMultiple(Request $request)
    {
        $ids = explode(',', $request->input('selected_equipments', ''));

        $equipments = Equipment::onlyTrashed()->whereIn('id', $ids)->get();

        LogBatch::startBatch();
        foreach ($equipments as $equipment) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'กู้คืนข้อมูล';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($equipment)
                ->withProperties(array_merge($equipment->only([
                    'number',
                    'name',
                    'amount',
                    'price',
                    'total_price',
                    'status_found',
                    'status_not_found',
                    'status_broken',
                    'status_disposal',
                    'status_transfer',
                    'description'
                ]), [
                    'unit' => optional($equipment->equipmentUnit)->name,
                    'location' => optional($equipment->location)->name,
                    'type' => optional($equipment->equipmentType)->name,
                    'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                    'user' => optional($equipment->user)->full_name
                ]))
                ->log('ครุภัณฑ์');
        }
        LogBatch::endBatch();

        Equipment::onlyTrashed()->whereIn('id', $ids)->restore();
        return redirect($request->input('redirect_to', route('equipment.trash')))->with('success', 'กู้คืนครุภัณฑ์ที่เลือกเรียบร้อยแล้ว');
    }

    // ฟังก์ชันลบข้อมูลครุภัณฑ์ถาวร
    public function forceDeleteMultiple(Request $request)
    {
        $ids = explode(',', $request->input('selected_equipments', ''));

        $equipments = Equipment::onlyTrashed()->whereIn('id', $ids)->get();

        LogBatch::startBatch();
        foreach ($equipments as $equipment) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'ลบข้อมูลถาวร';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($equipment)
                ->withProperties(array_merge($equipment->only([
                    'number',
                    'name',
                    'amount',
                    'price',
                    'total_price',
                    'status_found',
                    'status_not_found',
                    'status_broken',
                    'status_disposal',
                    'status_transfer',
                    'description'
                ]), [
                    'unit' => optional($equipment->equipmentUnit)->name,
                    'location' => optional($equipment->location)->name,
                    'type' => optional($equipment->equipmentType)->name,
                    'title' => optional($equipment->title)->group . ' - ' . optional($equipment->title)->group,
                    'user' => optional($equipment->user)->full_name
                ]))
                ->log('ครุภัณฑ์');
        }
        LogBatch::endBatch();

        Equipment::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        return redirect($request->input('redirect_to', route('equipment.trash')))->with('success', 'ลบครุภัณฑ์ถาวรเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสร้างข้อมูลหน่วยนับ
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

    // ส่งออกข้อมูลครุภัณฑ์
    public function export()
    {
        $request = request()->query();
        $title = Title::findOrFail($request['title_filter']);
        $request['title_filter'] = $title->group . ' - ' . $title->name;
        if ($request['unit_filter'] != 'all') {
            $unit = Equipment_unit::findOrFail($request['unit_filter']);
            $request['unit_filter'] = $unit->name;
        }else $request['unit_filter'] = 'ทั้งหมด';
        if ($request['location_filter'] != 'all') {
            $location = Location::findOrFail($request['location_filter']);
            $request['location_filter'] = $location->name;
        }else $request['location_filter'] = 'ทั้งหมด';
        if ($request['user_filter'] != 'all') {
            $user = User::findOrFail($request['user_filter']);
            $request['user_filter'] = $user->full_name;
        }else $request['user_filter'] = 'ทั้งหมด';
        activity()
            ->tap(function ($activity) {
                $activity->menu = 'ส่งออกข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            // 'unit' => optional($equipment->equipmentUnit)->name,
            ->performedOn($title)
            ->withProperties($request)
            ->log('ครุภัณฑ์');

        return Excel::download(new EquipmentsExport($title), 'equipments.xlsx');
    }
}
