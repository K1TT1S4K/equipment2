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
        $users = User::with('prefix')->paginate(10); // ดึงข้อมูลบุคลากรพร้อมคำนำหน้า
        return view('page.users.show', compact('users'));
    }
    // ค้นหาบุคลากร
    public function search(Request $request)
    {
        // รับค่าค้นหาจาก request
        $search = $request->get('search');
        $userType = $request->get('user_type');  // รับค่าระดับผู้ใช้

        // ค้นหาผู้ใช้โดยกรองข้อมูล
        $users = User::when($search, function ($query, $search) {
            return $query->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('firstname', 'like', "%{$search}%")
                         ->orWhere('lastname', 'like', "%{$search}%")
                         ->orWhere('user_type', 'like', "%{$search}%")
                         ->orWhereHas('prefix', function($q) use ($search) {
                             $q->where('name', 'like', "%{$search}%");
                         });
        })
        ->when($userType, function ($query, $userType) {
            return $query->where('user_type', $userType); // กรองตามระดับผู้ใช้
        })
        ->paginate(10); // ใช้การแบ่งหน้าหากข้อมูลมีจำนวนมาก

        return view('page.users.show', compact('users'));
    }
    // แสดงฟอร์มเพิ่มบุคลากร
    public function create()
    {
        $prefixes = Prefix::all(); // ดึงคำนำหน้าทั้งหมดจากฐานข้อมูล
        return view('page.users.add', compact('prefixes'));
    }
    // แสดงข้อมูลบุคลากร
    public function show($id)
    {
        $user = User::findOrFail($id); // ดึงข้อมูลบุคลากรตาม ID
        return view('page.users.show', compact('user'));
    }

    // บันทึกข้อมูลบุคลากรใหม่
    public function store(Request $request)
    {
        // dd($request->all()); // ตรวจสอบค่าที่ถูกส่งมาจากฟอร์ม
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่สาขา,ผู้ปฏิบัติงานบริหาร,อาจารย์',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'password' => Hash::make($request->password), // เข้ารหัสรหัสผ่าน
        ]);

        return redirect()->route('user')->with('success', 'เพิ่มบุคลากรเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขบุคลากร
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $prefixes = Prefix::all();
        return view('page.users.edit', compact('user', 'prefixes'));
    }

    // อัปเดตข้อมูลบุคลากร
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'prefix' => 'required|exists:prefixes,id',
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'user_type' => 'required|string|in:ผู้ดูแลระบบ,เจ้าหน้าที่สาขา,ผู้ปฏิบัติงานบริหาร,อาจารย์',
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8', // ถ้าไม่กรอก จะไม่เปลี่ยนรหัสผ่าน
        ]);

        $user->update([
            'username' => $request->username,
            'prefix_id' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // เปลี่ยนรหัสผ่านถ้ามีการกรอก
        ]);

        return redirect()->route('user')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }
    // ลบบุคลากร
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // ลบข้อมูลบุคลากร
        return redirect()->route('user')->with('success', 'ลบบุคลากรเรียบร้อยแล้ว');
    }

    public function profile()
    {
        $user = auth()->user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอิน
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8', // ไม่บังคับเปลี่ยนรหัสผ่าน
        ]);

        $user->update([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // เปลี่ยนรหัสผ่านถ้ามีการกรอก
        ]);

        return redirect()->route('profile')->with('success', 'อัปเดตโปรไฟล์เรียบร้อยแล้ว');
    }

}
