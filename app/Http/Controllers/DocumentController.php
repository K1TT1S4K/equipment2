<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::paginate(10);
        return view('page.documents.show', compact('documents'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

        $documents = Document::query();

        if (!empty($query)) { // ค้นหาจากชื่อไฟล์
            $documents->where('path', 'like', "%{$query}%");
        }

        if (!empty($documentType)) { // ค้นหาจากประเภทเอกสาร
            $documents->where('document_type', $documentType);
        }

        $documents = $documents->paginate(10); // แสดงผลลัพธ์ที่ค้นพบ

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
        $filePath = $file->store('documents', 'public'); // เก็บไฟล์ใน storage/app/public/documents

        Document::create([ // สร้างเอกสารใหม่ในฐานข้อมูล
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
            'path' => $filePath, // ที่อยู่ไฟล์ใน storage
        ]);

        return redirect()->route('document.index')->with('success', 'เพิ่มเอกสารสำเร็จ'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }

    public function edit($id) // แสดงฟอร์มสำหรับแก้ไขเอกสาร
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา
        return view('page.documents.edit', compact('document')); // ส่งเอกสารไปยัง view
    }

    public function update(Request $request, $id) // อัปเดตเอกสาร
    {
        $request->validate([ // ตรวจสอบความถูกต้องของข้อมูล
            'document_type' => 'required|string', // ประเภทเอกสาร
            'date' => 'required|date',  // วันที่
            'newFile' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // ขนาดไฟล์ไม่เกิน 10MB
        ]);

        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา

        if ($request->hasFile('newFile')) { // ถ้ามีการอัปโหลดไฟล์ใหม่
            Storage::delete('public/' . $document->path); // ลบไฟล์เก่าออกจาก storage
            $document->path = $request->file('newFile')->store('documents', 'public'); // เก็บไฟล์ใหม่ใน storage
        }

        $document->update([ // อัปเดตข้อมูลเอกสารในฐานข้อมูล
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
        ]);

        return redirect()->route('document.index')->with('success', 'อัปเดตเอกสารเรียบร้อยแล้ว'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
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
