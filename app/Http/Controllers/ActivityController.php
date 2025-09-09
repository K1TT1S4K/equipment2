<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    // แสดง log ทั้งหมด
    public function index()
    {
        $logs = Activity::orderBy('created_at', 'desc')->paginate(10); // ดึง log ล่าสุดก่อน

        return view('activity', compact('logs'));
    }

        public function search(Request $request)
    {
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $model = $request->input('model'); // ค้นหาจากประเภทเอกสาร

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

                if($model == 'ครุภัณฑ์'){
                    return $query->where('subject_type', 'App\Models\Equipment');
                }elseif($model == 'ส่งออกข้อมูล'){
                    return $query->where('subject_type', 'App\Models\Title');
                }elseif($model == 'ผู้ใช้'){
                    return $query->where('subject_type', 'App\Models\User');
                }elseif($model == 'เอกสาร'){
                    return $query->where('subject_type', 'App\Models\Document');
                }

                return $query->where('subject_type', $model);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // สำคัญ: เก็บ query string เอาไว้
        $logs->appends($request->all());

        return view('activity', compact('logs')); // ส่งผลลัพธ์ที่ค้นพบไปยัง view
    }
}
