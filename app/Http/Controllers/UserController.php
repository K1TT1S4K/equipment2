<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prefix;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // แสดงรายการบุคลากร
    public function index()
    {
        $users = User::with('prefix')->paginate(10);
        return view('page.users.show', compact('users'));
    }

    // ค้นหาบุคลากร
    public function search(Request $request)
    {
        $search = $request->get('search');
        $userType = $request->get('user_type');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('user_type', 'like', "%{$search}%")
                  ->orWhereHas('prefix', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($userType, function ($query, $userType) {
            return $query->where('user_type', $userType);
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        if ($request->ajax()) {
            return view('page.users.partials.user-list', compact('users'))->render();
        }

        return view('page.users.show', compact('users'));
    }

    // ฟอร์มเพิ่มผู้ใช้
    public function create()
    {
        $prefixes = Prefix::all();
        return view('page.users.add', compact('prefixes'));
    }

    // แสดงข้อมูลผู้ใช้
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('page.users.detail', compact('user'));
    }

    // บันทึกข้อมูลผู้ใช้ใหม่
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่สาขา,ผู้ปฏิบัติงานบริหาร,อาจารย์',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user')->with('success', 'เพิ่มบุคลากรเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขผู้ใช้
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $prefixes = Prefix::all();
        return view('page.users.edit', compact('user', 'prefixes'));
    }

    // อัปเดตข้อมูลผู้ใช้
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่สาขา,ผู้ปฏิบัติงานบริหาร,อาจารย์',
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('user')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    // ลบบุคลากร (Soft Delete)
    public function destroy(Request $request)
    {
        $selectedUsers = $request->input('selected_users', []);

        if (empty($selectedUsers)) {
            return redirect()->back()->with('warning', 'กรุณาเลือกผู้ใช้ที่ต้องการลบ');
        }

        User::whereIn('id', $selectedUsers)->delete();

        return redirect()->route('user.trashed')->with('success', 'ลบผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // โปรไฟล์ส่วนตัว
    public function profile()
    {
        $user = auth()->user();
        $prefixes = Prefix::all();
        return view('profile', compact('user', 'prefixes'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('profile')->with('success', 'อัปเดตโปรไฟล์เรียบร้อยแล้ว');
    }

    // แสดงผู้ใช้ที่ถูกลบ (Trash)
    public function trashed()
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('page.users.trash', compact('users'));
    }

    // กู้คืนผู้ใช้ที่ลบ (รายคน)
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('user.trashed')->with('success', 'กู้คืนผู้ใช้เรียบร้อยแล้ว');
    }

    // ลบถาวรผู้ใช้ (รายคน)
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('user.trashed')->with('success', 'ลบบัญชีผู้ใช้ถาวรเรียบร้อยแล้ว');
    }

    // กู้คืนผู้ใช้หลายรายการ
    public function restoreSelected(Request $request)
    {
        $ids = $request->input('selected_users', []);
        User::onlyTrashed()->whereIn('id', $ids)->restore();

        return redirect()->route('user.trashed')->with('success', 'กู้คืนผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // กู้คืนผู้ใช้ทั้งหมด
    public function restoreAll()
    {
        User::onlyTrashed()->restore();
        return redirect()->route('user.trashed')->with('success', 'กู้คืนผู้ใช้ทั้งหมดเรียบร้อยแล้ว');
    }

    // ลบถาวรจาก trash (เลือกเฉพาะที่ติ๊ก)
    public function deleteSelectedAll(Request $request)
    {
        $selectedUsers = $request->input('selected_users', []);

        if (empty($selectedUsers)) {
            return redirect()->back()->with('warning', 'กรุณาเลือกผู้ใช้ที่ต้องการลบ');
        }

        User::onlyTrashed()->whereIn('id', $selectedUsers)->forceDelete();

        return redirect()->route('user.trashed')->with('success', 'ลบผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // ลบถาวรผู้ใช้ทั้งหมดในถังขยะ
    public function deleteAll(Request $request)
    {
        $selectedUsers = $request->input('selected_users', []);

        if (empty($selectedUsers)) {
            return redirect()->back()->with('warning', 'กรุณาเลือกผู้ใช้ที่ต้องการลบ');
        }

        User::onlyTrashed()->whereIn('id', $selectedUsers)->forceDelete();

        return redirect()->route('user.trashed')->with('success', 'ลบผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    // ค้นหาในถังขยะ
    public function searchTrash(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        $users = $query->orderByDesc('deleted_at')->paginate(10);

        return view('page.users.trash', compact('users'));
    }
}
