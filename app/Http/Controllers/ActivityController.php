<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    // แสดง log ทั้งหมด
    public function index()
    {
        $users = User::all();
        $logs = Activity::orderBy('created_at', 'desc')->paginate(10); // ดึง log ล่าสุดก่อน

        return view('activity', compact('logs', 'users'));
    }

    public function search(Request $request)
    {
        // dd($request->input('menu'));
        $users = User::all();
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $model = $request->input('model'); // ค้นหาจากประเภทเอกสาร
        $menu = $request->input('menu');

        $logs = Activity::when($search, function ($query, $search) {
            return $query->where('log_name', 'like', "%{$search}%")
                ->orWhere('menu', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('subject_type', 'like', "%{$search}%")
                ->orWhere('properties', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('updated_at', 'like', "%{$search}%");
        })
            ->when($model, function ($query, $model) {
                if ($model == 'ครุภัณฑ์') {
                    return $query->where('description', 'ครุภัณฑ์');
                } elseif ($model == 'บุคลากร') {
                    return $query->where('description', 'บุคลากร');
                } elseif ($model == 'เอกสาร') {
                    return $query->where('description', 'เอกสาร');
                }
                return $query->where('subject_type', $model);
            })
            ->when($menu, function ($query, $menu){
                if($menu == 'เพิ่มข้อมูล'){
                    return $query->where('menu', 'เพิ่มข้อมูล');
                }elseif($menu == 'แก้ไขข้อมูล'){
                    return $query->where('menu', 'แก้ไขข้อมูล');
                }elseif($menu == 'ลบข้อมูลแบบซอฟต์'){
                    return $query->where('menu', 'ลบข้อมูลแบบซอฟต์');
                }elseif($menu == 'ลบข้อมูลถาวร'){
                    return $query->where('menu', 'ลบข้อมูลถาวร');
                }elseif($menu == 'ส่งออกข้อมูล'){
                    return $query->where('menu', 'ส่งออกข้อมูล');
                }elseif($menu == 'เข้าสู่ระบบ'){
                    return $query->where('menu', 'เข้าสู่ระบบ');
                }elseif($menu == 'ออกจากระบบ'){
                    return $query->where('menu', 'ออกจากระบบ');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // สำคัญ: เก็บ query string เอาไว้
        $logs->appends($request->all());

        return view('activity', compact('logs','users')); // ส่งผลลัพธ์ที่ค้นพบไปยัง view
    }
}
