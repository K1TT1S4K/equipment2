<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prefix;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Arr;

///
class UserController extends Controller
{
    // หน้าแสดงข้อมูลผู้ใช้
    public function index()
    {
        $users = User::with('prefix')->orderBy('created_at', 'desc')->paginate(10); // ดึงข้อมูลบุคลากรพร้อมคำนำหน้า
        return view('page.users.show', compact('users'));
    }
    // ฟังก์ชันค้นหาผู้ใช้
    public function search(Request $request)
    {
        // รับค่าค้นหาจาก request
        $search = $request->get('search');
        $userType = $request->get('user_type');  // รับค่าระดับผู้ใช้

        // ค้นหาผู้ใช้โดยกรองข้อมูล
        $users = User::when($search, function ($query, $search) {
            if ($search)
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('user_type', 'like', "%{$search}%")
                        ->orWhereHas('prefix', function ($q2) use ($search) {
                            $q2->whereRaw("CONCAT(prefixes.name, ' ', users.firstname, ' ', users.lastname) LIKE ?", ["%{$search}%"]);
                        })
                        ->orWhereRaw("
                    CONCAT(DAY(last_login_at), ' ',
                        CASE MONTH(last_login_at)
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
                        YEAR(last_login_at) + 543,
                        ' ',
                        DATE_FORMAT(last_login_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                    ;
                });
        })
            ->when($userType, function ($query, $userType) {
                return $query->where('user_type', $userType); // กรองตามระดับผู้ใช้
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // ใช้การแบ่งหน้าหากข้อมูลมีจำนวนมาก

        return view('page.users.show', compact('users'));
    }
    // หน้าเพิ่มผู้ใช้
    public function create()
    {
        $prefixes = Prefix::all(); // ดึงคำนำหน้าทั้งหมดจากฐานข้อมูล
        return view('page.users.add', compact('prefixes'));
    }

    // ฟังก์ชันเพิ่มผู้ใช้
    public function store(Request $request)
    {
        // dd($request->all()); // ตรวจสอบค่าที่ถูกส่งมาจากฟอร์ม
        $request->validate([
            // 'username' => 'required|string|max:50|unique:users,username|unique:users,username',
            'username' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) {
                // ตรวจสอบว่าเหมือนกับ username ที่มีอยู่แล้วหรือไม่
                $exists = User::where('username', $value)->exists();
                if ($exists) {
                    $fail('ชื่อผู้ใช้นี้มีอยู่แล้ว');
                }
            }],
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่พัสดุ,ผู้ปฏิบัติงานบริหาร,ผู้ดูแลครุภัณฑ์',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'password' => Hash::make($request->password), // เข้ารหัสรหัสผ่าน
        ]);

        $user = User::latest('id')->first();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'เพิ่มข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($user)
            ->withProperties(array_merge($user->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]))
            ->log('บุคลากร');

        return redirect($request->input('redirect_to', route('user')))->with('success', 'เพิ่มบุคลากรเรียบร้อยแล้ว');
    }

    // หน้าแก้ไขข้อมูลผู้ัใช้
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $prefixes = Prefix::all();
        return view('page.users.edit', compact('user', 'prefixes'));
    }

    // ฟังก์ชันแก้ไขข้อมูลผู้ใช้
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            // 'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) use ($user) {
                // ตรวจสอบว่าเหมือนกับ username ที่มีอยู่แล้วหรือไม่
                $exists = User::where('username', $value)->where('id', '!=', $user->id)->exists();
                if ($exists) {
                    $fail('ชื่อผู้ใช้นี้มีอยู่แล้ว');
                }
            }],
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่พัสดุ,ผู้ปฏิบัติงานบริหาร,ผู้ดูแลครุภัณฑ์',
            'password' => 'nullable|string|min:8', // ถ้าไม่กรอก จะไม่เปลี่ยนรหัสผ่าน
        ]);

        $oldValues = $user->toArray();

        $user->update([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // เปลี่ยนรหัสผ่านถ้ามีการกรอก
        ]);

        $newValues = $user->toArray();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'แก้ไขข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($user)
            ->withProperties([

                // Arr::only($oldValues, [
                //         'number',
                //         'name',
                //         'amount',
                //         'price',
                //         'total_price',
                //         'status_found',
                //         'status_not_found',
                //         'status_broken',
                //         'status_disposal',
                //         'status_transfer',
                //         'description'
                //     ]),
                'ข้อมูลก่อนแก้ไข' => array_merge(Arr::only($oldValues, ['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]),
                'ข้อมูลหลังแก้ไข' => array_merge(Arr::only($newValues, ['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]),
            ])
            ->log('บุคลากร');

        return redirect($request->input('redirect_to', route('user')))->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    // หน้าแก้ไขข้อมูลตนเอง
    public function profile()
    {
        $user = auth()->user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอิน
        $prefixes = Prefix::all(); // สมมุติว่ามี field prefix ในตาราง users
        return view('profile', compact('user', 'prefixes'));
    }

    // ฟังก์ชันแก้ไขข้อมูลตนเอง
    public function updateProfile(Request $request)
    {

        $user = auth()->user();

        // dd(($user->id));
        $request->validate([
            'username' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) use ($user) {
                // ตรวจสอบว่าเหมือนกับ username ที่มีอยู่แล้วหรือไม่
                // dd($value);
                $exists = User::where('username', $value)->where('id', '!=', $user->id)->exists();

                if ($exists) {
                    $fail('ชื่อผู้ใช้นี้มีอยู่แล้ว');
                }
            }],

            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
        ]);

        if ($request->old_password && $request->password) {
            $request->validate([
                'old_password' => ['nullable', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, auth()->user()->password)) {
                        $fail('รหัสผ่านเก่าไม่ถูกต้อง');
                    }
                }],
                'password' => 'nullable|string|min:8|confirmed'
            ]);
        };

        $passwordChanged = false;

        if ($request->old_password) {
            if (!Hash::check($request->old_password, $user->password)) {
                // เก็บข้อความผิดพลาดลง session
                return back()->with('error', 'เปลี่ยนรหัสผ่านไม่สำเร็จ: รหัสผ่านเก่าไม่ถูกต้อง');
            } elseif (!$request->filled('password') || !$request->filled('password_confirmation') || $request->password !== $request->password_confirmation) {
                return back()->with('error', 'เปลี่ยนรหัสผ่านไม่สำเร็จ: กรุณากรอกรหัสผ่านใหม่และยืนยันรหัสผ่านให้ตรงกัน');
            }

            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $oldValues = $user->toArray();

        $user->update([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'prefix_id' => $request->prefix,
        ]);

        if ($request->old_password && $request->password) {
            $user->update([
                'password' => $user->password,
            ]);
        }

        // dd($user, $user->prefix_id, $request->prefix);

        $newValues = $user->toArray();

        // แสดงข้อความสำเร็จ
        if ($passwordChanged) {
            // activity()
            //     ->tap(function ($activity) {
            //         $activity->menu = 'แก้ไขข้อมูล';
            //     })
            //     ->useLog(auth()->user()->full_name)
            //     ->performedOn($user)
            //     ->withProperties([
            //         'ข้อมูลก่อนแก้ไข' => array_merge($oldValues->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]),
            //         'ข้อมูลหลังแก้ไข' => array_merge($newValues->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]),
            //     ])
            //     ->log('บุคลากร');

            return redirect()->route('profile')->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
        } else {
            // activity()
            //     ->tap(function ($activity) {
            //         $activity->menu = 'แก้ไขข้อมูล';
            //     })
            //     ->useLog(auth()->user()->full_name)
            //     ->performedOn($user)
            //     ->withProperties([
            //         'ข้อมูลก่อนแก้ไข' => $oldValues->only(['username', 'firstname', 'lastname', 'user_type']),
            //         'ข้อมูลหลังแก้ไข' => $newValues->only(['username', 'firstname', 'lastname', 'user_type'])
            //     ])
            //     ->log('บุคลากร');

            return redirect()->route('profile')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
        }
    }

    // หน้ากู้ข้อมูลผู้ใช้
    public function trashed()
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('page.users.trash', compact('users'));
    }

    // ฟัง์ชันลบข้อมูลผู้ใช้ถาวร
    public function forceDeleteSelected(Request $request)
    {
        // รับข้อมูล ID ของผู้ใช้ที่เลือก
        $ids = explode(',', $request->input('selected_users', ''));

        $users = User::onlyTrashed()->whereIn('id', $ids)->get();
        LogBatch::startBatch();
        foreach ($users as $user) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'ลบข้อมูลถาวร';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($user)
                ->withProperties(array_merge($user->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]))
                ->log('บุคลากร');
        }
        LogBatch::endBatch();


        // ลบถาวรผู้ใช้ที่เลือก
        User::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        // กลับไปที่หน้าผู้ใช้ที่ถูกลบพร้อมข้อความ success
        return redirect($request->input('redirect_to', route('user.trashed')))->with('success', 'ลบข้อมูลถาวรผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // ฟังก์ชันลบข้อมูลผู้ใช้แบบซอฟต์
    public function deleteSelected(Request $request)
    {
        $selectedUsers = $request->input('selected_users', []);

        if (empty($selectedUsers)) {
            return redirect()->back()->with('warning', 'กรุณาเลือกผู้ใช้ที่ต้องการลบ');
        }

        $users = User::whereIn('id', $selectedUsers)->get();

        LogBatch::startBatch();
        foreach ($users as $user) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'ลบข้อมูลแบบซอฟต์';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($user)
                ->withProperties(array_merge($user->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]))
                ->log('บุคลากร');
        }
        LogBatch::endBatch();

        User::whereIn('id', $selectedUsers)->delete();
        return redirect($request->input('redirect_to', route('user')))->with('success', 'ลบผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // ฟังก์ชันกู้คืนข้อมูลผู้ใช้
    public function restoreSelected(Request $request)
    {
        // รับข้อมูล ID ของผู้ใช้ที่เลือก
        $ids = explode(',', $request->input('selected_users', ''));

        $users = User::onlyTrashed()->whereIn('id', $ids)->get();

        LogBatch::startBatch();
        foreach ($users as $user) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'กู้คืนข้อมูล';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($user)
                ->withProperties(array_merge($user->only(['username', 'firstname', 'lastname', 'user_type']), ['prefix' => optional($user->prefix)->name]))
                ->log('บุคลากร');
        }
        LogBatch::endBatch();


        // กู้คืนผู้ใช้ที่เลือก
        User::onlyTrashed()->whereIn('id', $ids)->restore();

        // กลับไปที่หน้าผู้ใช้ที่ถูกลบพร้อมข้อความ success
        return redirect($request->input('redirect_to', route('user.trashed')))->with('success', 'กู้คืนผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // ฟังก์ชันค้นหาข้อมูลผู้ใช้ในหน้ากู้ข้อมูล
    public function searchTrash(Request $request)
    {
        // รับค่าค้นหาจาก request
        $search = $request->get('search');
        $userType = $request->get('user_type');  // รับค่าระดับผู้ใช้

        // ค้นหาผู้ใช้โดยกรองข้อมูล
        $users = User::onlyTrashed()->when($search, function ($query, $search) {
            if ($search)
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('user_type', 'like', "%{$search}%")
                        ->orWhereHas('prefix', function ($q2) use ($search) {
                            $q2->whereRaw("CONCAT(prefixes.name, ' ', users.firstname, ' ', users.lastname) LIKE ?", ["%{$search}%"]);
                        })
                        ->orWhereRaw("
                    CONCAT(DAY(deleted_at), ' ',
                        CASE MONTH(deleted_at)
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
                        YEAR(deleted_at) + 543,
                        ' ',
                        DATE_FORMAT(deleted_at, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
                    ;
                });
        })
            ->when($userType, function ($query, $userType) {
                return $query->where('user_type', $userType); // กรองตามระดับผู้ใช้
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate(10); // ใช้การแบ่งหน้าหากข้อมูลมีจำนวนมาก

        return view('page.users.trash', compact('users'));
    }
}
