<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Location;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquipmentsExport;

use App\Models\Document as ModelsDocument;
use App\Models\Equipment_document;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;



class EquipmentController extends Controller
{
    // หน้าแสดงข้อมูล
    public function index(Request $request)
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $locations = Location::all();
        $titles = Title::all();
        $equipment_trash = Equipment::onlyTrashed()->get();
        $fullEquipments = Equipment::all();

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
                        ->orWhere('amount', 'link', "%{$search}%")
                        ->orWhereRaw("
                    CONCAT(DAY(created_at), ' ', 
                        CASE MONTH(created_at)
                            WHEN 1 THEN 'ม.ค.'
                            WHEN 2 THEN 'ก.พ.'
                            WHEN 3 THEN 'มี.ค.'
                            WHEN 4 THEN 'เม.ย.'
                            WHEN 5 THEN 'พ.ค.'
                            WHEN 6 THEN 'มิ.ย.'
                            WHEN 7 THEN 'ก.ค.'
                            WHEN 8 THEN 'ส.ค.'
                            WHEN 9 THEN 'ก.ย.'
                            WHEN 10 THEN 'ต.ค.'
                            WHEN 11 THEN 'พ.ย.'
                            WHEN 12 THEN 'ธ.ค.'
                        END,
                        ' ',
                        YEAR(created_at) + 543,
                        ' ',
                        DATE_FORMAT(created_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("
                    CONCAT(DAY(updated_at), ' ', 
                        CASE MONTH(updated_at)
                            WHEN 1 THEN 'ม.ค.'
                            WHEN 2 THEN 'ก.พ.'
                            WHEN 3 THEN 'มี.ค.'
                            WHEN 4 THEN 'เม.ย.'
                            WHEN 5 THEN 'พ.ค.'
                            WHEN 6 THEN 'มิ.ย.'
                            WHEN 7 THEN 'ก.ค.'
                            WHEN 8 THEN 'ส.ค.'
                            WHEN 9 THEN 'ก.ย.'
                            WHEN 10 THEN 'ต.ค.'
                            WHEN 11 THEN 'พ.ย.'
                            WHEN 12 THEN 'ธ.ค.'
                        END,
                        ' ',
                        YEAR(updated_at) + 543,
                        ' ',
                        DATE_FORMAT(updated_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                        ->orWhereHas('equipmentUnit', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('location', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->join('prefixes', 'users.prefix_id', '=', 'prefixes.id')
                                ->whereRaw("CONCAT(prefixes.name, ' ', users.firstname) LIKE ?", ["%{$search}%"]);
                        })
                    ;
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
                if ($user == null) $query->where('user_id', null);
            })
            ->get('id');

        $idFilterd = [];

        foreach ($equipments as $key => $equipment) {
            $idFilterd[] = $equipment->id;
        }

        $statusFilter = Equipment::all();

        $equipments = Equipment::whereIn('id', $idFilterd)->orderBy('created_at', 'desc')->paginate(10);
        $equipmentsNoPaginate = Equipment::whereIn('id', $idFilterd)->orderBy('created_at', 'desc')->get();

        $equipments->appends($request->all());

        return view('page.equipments.show', compact('equipmentsNoPaginate', 'fullEquipments', 'equipment_trash', 'equipments', 'equipment_units', 'locations', 'users', 'titles'));
    }

    // หน้ากู้คืนข้อมูล
    public function trash(Request $request)
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $locations = Location::all();
        $titles = Title::all();
        $equipment_trash = Equipment::onlyTrashed()->get();
        $fullEquipments = Equipment::all();


        $search = null;
        if (!empty($request['query'])) $search = $request->input('query');
        $title = $request->input('title_filter'); // ค้นหาจากประเภทหัวข้อ
        $unit = $request->input('unit_filter'); //ค้นหาจากประเภทหน่วยนับ
        $location = $request->input('location_filter');
        $user = $request->input('user_filter');

        $equipments = Equipment::onlyTrashed()->when($search, function ($query, $search) {
            if ($search != 'all')
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")
                        ->orWhere('amount', 'link', "%{$search}%")
                        ->orWhereRaw("
                    CONCAT(DAY(created_at), ' ', 
                        CASE MONTH(created_at)
                            WHEN 1 THEN 'ม.ค.'
                            WHEN 2 THEN 'ก.พ.'
                            WHEN 3 THEN 'มี.ค.'
                            WHEN 4 THEN 'เม.ย.'
                            WHEN 5 THEN 'พ.ค.'
                            WHEN 6 THEN 'มิ.ย.'
                            WHEN 7 THEN 'ก.ค.'
                            WHEN 8 THEN 'ส.ค.'
                            WHEN 9 THEN 'ก.ย.'
                            WHEN 10 THEN 'ต.ค.'
                            WHEN 11 THEN 'พ.ย.'
                            WHEN 12 THEN 'ธ.ค.'
                        END,
                        ' ',
                        YEAR(created_at) + 543,
                        ' ',
                        DATE_FORMAT(created_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("
                    CONCAT(DAY(updated_at), ' ', 
                        CASE MONTH(updated_at)
                            WHEN 1 THEN 'ม.ค.'
                            WHEN 2 THEN 'ก.พ.'
                            WHEN 3 THEN 'มี.ค.'
                            WHEN 4 THEN 'เม.ย.'
                            WHEN 5 THEN 'พ.ค.'
                            WHEN 6 THEN 'มิ.ย.'
                            WHEN 7 THEN 'ก.ค.'
                            WHEN 8 THEN 'ส.ค.'
                            WHEN 9 THEN 'ก.ย.'
                            WHEN 10 THEN 'ต.ค.'
                            WHEN 11 THEN 'พ.ย.'
                            WHEN 12 THEN 'ธ.ค.'
                        END,
                        ' ',
                        YEAR(updated_at) + 543,
                        ' ',
                        DATE_FORMAT(updated_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                        ->orWhereHas('equipmentUnit', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('location', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->join('prefixes', 'users.prefix_id', '=', 'prefixes.id')
                                ->whereRaw("CONCAT(prefixes.name, ' ', users.firstname) LIKE ?", ["%{$search}%"]);
                        })
                    ;
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
                if ($user == null) $query->where('user_id', null);
            })
            ->get('id');

        $idFilterd = [];

        foreach ($equipments as $key => $equipment) {
            $idFilterd[] = $equipment->id;
        }

        $statusFilter = Equipment::onlyTrashed()->get();

        // dd($statusFilter);

        // dd($idFilterd);
        $equipments = Equipment::onlyTrashed()->whereIn('id', $idFilterd)->orderBy('created_at', 'desc')->paginate(10);
        $equipmentsNoPaginate = Equipment::onlyTrashed()->whereIn('id', $idFilterd)->orderBy('created_at', 'desc')->get();
        // dd($equipments);
        $equipments->appends($request->all());

        return view('page.equipments.trash', compact('fullEquipments', 'equipment_trash', 'equipments', 'equipment_units', 'locations', 'users', 'titles'));
    }

    // หน้าสร้างข้อมูล
    public function create()
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();

        return view('page.equipments.add', compact('equipments', 'equipment_units', 'locations', 'users', 'titles'));
    }

    // หน้าแก้ไขข้อมูล
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();
        $documents = ModelsDocument::all();

        return view('page.equipments.edit', compact('equipments',  'equipment', 'equipment_units', 'locations', 'users', 'titles')) . $id;
    }

    // ฟังก์ชันสร้างข้อมูล
    public function store(Request $request)
    {
        // dd($request);   
        $request->validate([
            'number' => 'required|string|max:255',
            'name' => 'required|string|max:2000',
            'amount' => 'required|integer|max:9999999999',
            'price' => 'nullable|numeric|min:0|max:99999999.99',
            'equipment_unit_id'  => 'required|integer|max:9999999999',
            'location_id'  => 'nullable|integer|max:9999999999',
            'title_id'  => 'required|integer|max:9999999999',
            'user_id'  => 'nullable|integer|max:9999999999',
            'description'  => 'nullable|string|max:255',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->store('', 'public');
        }

        $total_price = $request->price * $request->amount;

        $data = [
            'number' =>  $request->number,
            'name' =>  $request->name,
            'amount' =>  $request->amount,
            'price' =>  $request->price,
            'total_price' =>  $total_price,
            'equipment_unit_id'  =>  $request->equipment_unit_id,
            'location_id'  =>  $request->location_id,
            'title_id'  =>  $request->title_id,
            'user_id'  =>  $request->user_id,
            'description'  =>  $request->description,
        ];

        // ถ้ามีการอัปโหลดไฟล์
        if ($request->file('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->store(); // เก็บไว้ใน storage/app/public/img

            $data['original_image_name'] = $originalName;
            $data['stored_image_name']   = $filePath;
        }

        Equipment::create($data);

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
                'description',
                'original_image_name'
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

    public function updateStatus(Request $request)
    {
        $data = $request->input('status', []);
        $this->update_status($data);

        return redirect()->route('equipment.index', [
            'title_filter'    => Title::max('id'),
            'unit_filter'     => 'all',
            'location_filter' => 'all',
            'user_filter'     => 'all',
        ]);
    }

    public function update_status(array $data)
    {
        foreach ($data as $equipmentId => $statuses) {
            // หา equipment ตาม id
            $equipment = Equipment::find($equipmentId);

            if (!$equipment) {
                // ถ้าไม่พบให้ข้าม
                continue;
            }

            // อัปเดตสถานะแต่ละฟิลด์
            $equipment->status_found = $statuses['status_found'] ?? $equipment->status_found;
            $equipment->status_not_found = $statuses['status_not_found'] ?? $equipment->status_not_found;
            $equipment->status_broken = $statuses['status_broken'] ?? $equipment->status_broken;
            $equipment->status_disposal = $statuses['status_disposal'] ?? $equipment->status_disposal;
            $equipment->status_transfer = $statuses['status_transfer'] ?? $equipment->status_transfer;

            // บันทึกการเปลี่ยนแปลง
            $equipment->save();
        }
        // return
    }

    // ฟังก์ชันแก้ไขข้อมุล
    public function update(Request $request, $id)
    {

        // dd($request);

        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'number' => 'required|string|max:255',
            'name' => 'required|string|max:2000',
            'amount' => 'required|integer|max:9999999999',
            'price' => 'nullable|numeric|min:0|max:99999999.99',
            'equipment_unit_id'  => 'required|integer|max:9999999999',
            'location_id'  => 'nullable|integer|max:9999999999',
            'title_id'  => 'required|integer|max:9999999999',
            'user_id'  => 'nullable|integer|max:9999999999',
            'description'  => 'nullable|string|max:255',
        ]);

        // dd($request);

        $total_price = $request->price * $request->amount;

        $oldValues = $equipment->toArray(); // คัดลอกเป็น array แยกออกมา

        $data = [
            'number' =>  $request->number,
            'name' =>  $request->name,
            'amount' =>  $request->amount,
            'price' =>  $request->price,
            'total_price' =>  $total_price,
            'equipment_unit_id'  =>  $request->equipment_unit_id,
            'location_id'  =>  $request->location_id,
            'title_id'  =>  $request->title_id,
            'user_id'  =>  $request->user_id,
            'description'  =>  $request->description,
        ];

        // ถ้ามีการอัปโหลดไฟล์
        if ($request->file('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->store('', 'public');

            $data['original_image_name'] = $originalName;
            $data['stored_image_name']   = $filePath;
        }

        $equipment->update($data);

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
                        'description',
                        'original_image_name'
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
                        'description',
                        'original_image_name'
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
                        'description',
                        'original_image_name'
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
                    'description',
                    'original_image_name'
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
                    'description',
                    'original_image_name'
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

    // ส่งออกข้อมูลครุภัณฑ์
    public function export()
    {
        $request = request()->query();
        $title = Title::findOrFail($request['title_filter']);
        $request['title_filter'] = $title->group . ' - ' . $title->name;
        if ($request['unit_filter'] != 'all') {
            $unit = Equipment_unit::findOrFail($request['unit_filter']);
            $request['unit_filter'] = $unit->name;
        } else $request['unit_filter'] = 'ทั้งหมด';
        if ($request['location_filter'] != 'all') {
            $location = Location::findOrFail($request['location_filter']);
            $request['location_filter'] = $location->name;
        } else $request['location_filter'] = 'ทั้งหมด';
        if ($request['user_filter'] != 'all') {
            $user = User::findOrFail($request['user_filter']);
            $request['user_filter'] = $user->full_name;
        } else $request['user_filter'] = 'ทั้งหมด';
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
