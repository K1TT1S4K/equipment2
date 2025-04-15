<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('page.documents.show', compact('documents'));
    }

    public function search(Request $request)
    {
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

        $documents = Document::when($search, function($query, $search){
            return $query->where('document_type', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('path', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%")
                        ->orWhere('updated_at', 'like', "%{$search}%");
        })
        ->when($documentType, function ($query, $documentType) {
            return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
        })
        ->paginate();

        // $documents = Document::query();

        // if (!empty($query)) { // ค้นหาจากชื่อไฟล์
        //     $documents->where('path', 'like', "%{$query}%");
        // }

        // if (!empty($documentType)) { // ค้นหาจากประเภทเอกสาร
        //     $documents->where('document_type', $documentType);
        // }

        // $documents = $documents->paginate(10); // แสดงผลลัพธ์ที่ค้นพบ

        return view('page.documents.show', compact('documents')); // ส่งผลลัพธ์ที่ค้นพบไปยัง view
    }

    public function create() // แสดงฟอร์มสำหรับเพิ่มเอกสาร
    {
        return view('page.documents.add');
    }

    public function store(Request $request) // บันทึกเอกสารใหม่
    {
        $request->validate([ // ตรวจสอบความถูกต้องของข้อมูล
            'document_type' => 'required|string', // ประเภทเอกสาร
            'date' => 'required|date', // วันที่
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048' // ขนาดไฟล์ไม่เกิน 2MB
        ]);

        $file = $request->file('document'); // รับไฟล์เอกสาร
        // $filePath = $file->store('documents', 'public'); // เก็บไฟล์ใน storage/app/public/documents
        $originalName = $file->getClientOriginalName(); // ดึงชื่อไฟล์เดิม
        $filePath = $file->storeAs('documents', $originalName, 'public'); // เก็บด้วยชื่อเดิม

        Document::create([ // สร้างเอกสารใหม่ในฐานข้อมูล
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
            'path' => $filePath, // ที่อยู่ไฟล์ใน storage
            // 'path' => 'documents/' . $originalName, // ที่อยู่ไฟล์ใน storage
        ]);

        return redirect()->route('document.index')->with('success', 'เพิ่มเอกสารสำเร็จ'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }

    public function edit($id) // แสดงฟอร์มสำหรับแก้ไขเอกสาร
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา
        return view('page.documents.edit', compact('document')); // ส่งเอกสารไปยัง view
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'document_type' => 'required|string',
        'date' => 'required|date',
        'newFile' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
    ]);

    $document = Document::findOrFail($id);

    if ($request->hasFile('newFile')) {
        // ลบไฟล์เก่าออก
        if ($document->path) {
            Storage::delete('public/' . $document->path);
        }

        // ดึงชื่อไฟล์เดิม + ต่อ timestamp เพื่อกันซ้ำ
        $file = $request->file('newFile');
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $uniqueName = $filename . '.' . $extension;

        // เก็บไฟล์ใหม่
        $filePath = $file->storeAs('documents', $uniqueName, 'public');

        // อัปเดต path ใหม่ลงใน model
        $document->path = $filePath;
    }

    // อัปเดตข้อมูลอื่น
    $document->update([
        'document_type' => $request->document_type,
        'date' => $request->date,
        'path' => $document->path, // เผื่อไม่ได้อัปโหลดไฟล์ใหม่ จะใช้ path เดิม
    ]);

    return redirect()->route('document.index')->with('success', 'อัปเดตเอกสารเรียบร้อยแล้ว');
}


    public function show($id) // แสดงรายละเอียดเอกสาร
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา
        return view('page.documents.show', compact('document')); // ส่งเอกสารไปยัง view
    }

    public function destroy($id) // ลบเอกสาร
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา

        // ลบไฟล์จาก storage ก่อน
        if ($document->path) { // ถ้ามีไฟล์
            Storage::delete('public/' . $document->path); // ลบไฟล์จาก storage
        }

        $document->delete(); // ลบเอกสารจากฐานข้อมูล

        return redirect()->route('document.index')->with('success', 'เอกสารได้ถูกลบ'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }
}
