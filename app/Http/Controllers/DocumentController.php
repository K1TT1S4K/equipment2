<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        // $documents = Document::all();
        $documents = Document::paginate(10); // แสดงเอกสารทั้งหมดในหน้าแรก โดยแบ่งหน้า 10 รายการต่อหน้า
        return view('page.documents.show', compact('documents'));
    }

    public function search(Request $request)
    {
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    $documents = Document::when($search, function ($query, $search) {
                        return $query->where('document_type', 'like', "%{$search}%")
                                     ->orWhere('path', 'like', "%{$search}%");
                    })
                    ->when($documentType, function ($query, $documentType) {
                        return $query->where('document_type', $documentType);
                    })
                    ->latest()
                    ->paginate(10);

    return view('page.documents.partials.rows', compact('documents'))->render(); // ต้องใช้ render()
}
=======
        $documents = Document::when($search, function ($query, $search) {
            return $query->where('document_type', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('original_name', 'like', "%{$search}%")
=======
=======
>>>>>>> parent of 2a888ab (เพิ่มค้นหาแบบเรียลไทม์หน้าเอกสาร)
        $documents = Document::when($search, function ($query, $search) {
            return $query->where('document_type', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('path', 'like', "%{$search}%")
<<<<<<< HEAD
>>>>>>> parent of 2a888ab (เพิ่มค้นหาแบบเรียลไทม์หน้าเอกสาร)
=======
>>>>>>> parent of 2a888ab (เพิ่มค้นหาแบบเรียลไทม์หน้าเอกสาร)
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('updated_at', 'like', "%{$search}%");
        })
            ->when($documentType, function ($query, $documentType) {
                return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
            })
            ->paginate();
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> 4f630470fca786ed4cca9159c74c23d822e61d20
=======
>>>>>>> parent of 2a888ab (เพิ่มค้นหาแบบเรียลไทม์หน้าเอกสาร)
=======
>>>>>>> parent of 2a888ab (เพิ่มค้นหาแบบเรียลไทม์หน้าเอกสาร)

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
            'document' => 'required|file|mimes:pdf,doc,docx' // ขนาดไฟล์ไม่เกิน 2MB
        ]);

        $file = $request->file('document'); // รับไฟล์เอกสาร
        $originalName = $file->getClientOriginalName(); // ดึงชื่อไฟล์เดิม

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        // dd($file);

        $file->storeAs('documents',$filename,'public');

        // dd($path);
        Document::create([ // สร้างเอกสารใหม่ในฐานข้อมูล
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
            'original_name' => $originalName, // ที่อยู่ไฟล์ใน storage
            'stored_name' => $filename
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
            if ($document->original_name) {
                Storage::delete('public/' . $document->original_name);
            }

            // ดึงชื่อไฟล์เดิม + ต่อ timestamp เพื่อกันซ้ำ
            $file = $request->file('newFile');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $filename . '.' . $extension;

            // เก็บไฟล์ใหม่
            $filePath = $file->storeAs('documents', $uniqueName, 'public');

            // อัปเดต path ใหม่ลงใน model
            $document->original_name = $filePath;
        }

        // อัปเดตข้อมูลอื่น
        $document->update([
            'document_type' => $request->document_type,
            'date' => $request->date,
            'original_name' => $document->original_name, // เผื่อไม่ได้อัปโหลดไฟล์ใหม่ จะใช้ path เดิม
        ]);

        return redirect()->route('document.index')->with('success', 'อัปเดตเอกสารเรียบร้อยแล้ว');
    }


    public function show($id) // แสดงรายละเอียดเอกสาร
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา
        return view('page.documents.show', compact('document')); // ส่งเอกสารไปยัง view
    }

    public function trash() // แสดงเอกสารที่ถูกลบ
    {
        $documents = Document::onlyTrashed()->paginate(10); // ค้นหาเอกสารที่ถูกลบ
        return view('page.documents.trash', compact('documents')); // ส่งเอกสารที่ถูกลบไปยัง view
    }

    public function restore($id) // กู้คืนเอกสารที่ถูกลบ
    {
        $document = Document::onlyTrashed()->findOrFail($id); // ค้นหาเอกสารที่ถูกลบตาม ID ที่ส่งมา
        $document->restore(); // กู้คืนเอกสาร
        return redirect()->route('document.trash')->with('success', 'กู้คืนเอกสารเรียบร้อยแล้ว');
    }
    public function restoreAll()
    {
        Document::onlyTrashed()->restore(); // กู้คืนเอกสารทั้งหมด
        return redirect()->route('document.trash')->with('success', 'กู้คืนเอกสารทั้งหมดเรียบร้อยแล้ว');
    }
    public function deleteAll()
    {
        Document::onlyTrashed()->forceDelete(); // ลบเอกสารทั้งหมดถาวร
        return redirect()->route('document.trash')->with('success', 'ลบเอกสารทั้งหมดเรียบร้อยแล้ว');
    }
    public function deleteSelectedAll(Request $request)
    {
        $ids = $request->input('selected_documents', []); // รับ ID ของเอกสารที่เลือก
        Document::whereIn('id', $ids)->forceDelete(); // ลบเอกสารที่เลือกถาวร

        return redirect()->route('document.trash')->with('success', 'ลบเอกสารที่เลือกเรียบร้อยแล้ว');
    }
    public function forceDelete($id) // ลบเอกสารถาวร
    {
        $document = Document::withTrashed()->findOrFail($id); // ค้นหาเอกสารที่ถูกลบตาม ID ที่ส่งมา
        Storage::delete('public/' . $document->original_name); // ลบไฟล์จาก storage
        $document->forceDelete(); // ลบเอกสารจากฐานข้อมูล

        return redirect()->route('document.trash')->with('success', 'ลบเอกสารถาวรเรียบร้อยแล้ว'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }
    public function restoreMultiple(Request $request)
    {
        $ids = explode(',', $request->input('selected_documents', ''));
        Document::onlyTrashed()->whereIn('id', $ids)->restore();
        return redirect()->route('document.trash')->with('success', 'กู้คืนเอกสารที่เลือกเรียบร้อยแล้ว');
    }

    public function deleteSelected(Request $request)
    {
        $documentIds = $request->input('selected_documents');

        if ($documentIds) {
            Document::whereIn('id', $documentIds)->delete(); // <-- ใช้ SoftDelete ถ้าเปิดใช้งาน
            return redirect()->route('document.index')->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
        }

        return redirect()->route('document.index')->with('error', 'กรุณาเลือกเอกสาร');
    }

    public function restoreAllDocuments() // กู้คืนเอกสารทั้งหมด
    {
        Document::onlyTrashed()->restore(); // กู้คืนเอกสารทั้งหมด
        return redirect()->route('document.trash')->with('success', 'กู้คืนเอกสารทั้งหมดเรียบร้อยแล้ว'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }
    public function searchTrash(Request $request)
    {
        $query = Document::onlyTrashed();

        if ($request->filled('query')) {
            $query->where('document_type', 'like', '%' . $request->query('query') . '%')
                ->orWhere('original_name', 'like', '%' . $request->query('query') . '%');
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->query('document_type'));
        }

        $documents = $query->get();

        return view('page.documents.trash', compact('documents'));
    }
}
