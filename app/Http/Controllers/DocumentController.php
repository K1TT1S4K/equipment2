<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $documents = Document::when($search, function ($query, $search) {
            return $query->where('document_type', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('stored_name', 'like', "%{$search}%")
                ->orWhere('original_name', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('updated_at', 'like', "%{$search}%");
        })
            ->when($documentType, function ($query, $documentType) {
                return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
            })
            ->paginate(10);

        // สำคัญ: เก็บ query string เอาไว้
        $documents->appends($request->all());

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
            'document' => 'required|file|mimes:pdf|max:2048' // ขนาดไฟล์ไม่เกิน 2MB
        ]);

        $file = $request->file('document'); // รับไฟล์เอกสาร
        // $filePath = $file->store('documents', 'public'); // เก็บไฟล์ใน storage/app/public/documents
        $originalName = $file->getClientOriginalName(); // ดึงชื่อไฟล์เดิม
        // $filePath = $file->storeAs('documents', $originalName, 'public'); // เก็บด้วยชื่อเดิม
        $filePath = $file->store('', 'public'); // เก็บด้วยชื่อเดิม

        Document::create([ // สร้างเอกสารใหม่ในฐานข้อมูล
            // dd('99'),
            'original_name' => $originalName,
            'stored_name' => $filePath,
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
            // 'path' => $filePath, // ที่อยู่ไฟล์ใน storage
            // 'path' => 'documents/' . $originalName, // ที่อยู่ไฟล์ใน storage

        ]);

        return redirect($request->input('redirect_to',route('document.index')))->with('success', 'เพิ่มเอกสารสำเร็จ'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
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
            'newFile' => 'nullable|file|mimes:pdf|max:10240',
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

        return redirect($request->input('redirect_to', route('document.index')))->with('success', 'อัปเดตเอกสารเรียบร้อยแล้ว');
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
    // public function deleteSelectedAll(Request $request)
    // {
    //     $ids = $request->input('selected_documents', []); // รับ ID ของเอกสารที่เลือก
    //     Document::whereIn('id', $ids)->forceDelete(); // ลบเอกสารที่เลือกถาวร

    //     return redirect()->route('document.trash')->with('success', 'ลบเอกสารที่เลือกเรียบร้อยแล้ว');
    // }
    public function forceDelete($id) // ลบเอกสารถาวร
    {
        $document = Document::withTrashed()->findOrFail($id); // ค้นหาเอกสารที่ถูกลบตาม ID ที่ส่งมา
        Storage::delete('public/' . $document->path); // ลบไฟล์จาก storage
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
// dd('99');

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
    // public function searchTrash(Request $request)
    // {
    //     $query = Document::onlyTrashed();

    //     if ($request->filled('query')) {
    //         $query->where('document_type', 'like', '%' . $request->query('query') . '%')
    //             ->orWhere('path', 'like', '%' . $request->query('query') . '%');
    //     }

    //     if ($request->filled('document_type')) {
    //         $query->where('document_type', $request->query('document_type'));
    //     }

    //     $documents = $query->get();

    //     return view('page.documents.trash', compact('documents'));
    // }

        public function searchTrash(Request $request)
    {
        $query = Document::onlyTrashed();

        if($request->filled('search')) {
            $search = $request->input('search');
            // dd($search);
            $query->where(function ($q) use ($search) {
                $q->where('document_type', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('stored_name', 'like', "%{$search}%")
                ->orWhere('original_name', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('updated_at', 'like', "%{$search}%");
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }

        $documents = $query->orderByDesc('deleted_at')->paginate(10);

        return view('page.documents.trash', compact('documents'));

        // $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        // $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

        // $documents = Document::onlyTrashed()->when($search, function ($query, $search) {
        //     return $query->where('document_type', 'like', "%{$search}%")
        //         ->orWhere('date', 'like', "%{$search}%")
        //         ->orWhere('stored_name', 'like', "%{$search}%")
        //         ->orWhere('original_name', 'like', "%{$search}%")
        //         ->orWhere('created_at', 'like', "%{$search}%")
        //         ->orWhere('updated_at', 'like', "%{$search}%");
        // })
        //     ->when($documentType, function ($query, $documentType) {
        //         return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
        //     })
        //     ->paginate(10);

        // // สำคัญ: เก็บ query string เอาไว้
        // $documents->appends($request->all());

        // return view('page.documents.trash', compact('documents')); // ส่งผลลัพธ์ที่ค้นพบไปยัง view
    }
}
