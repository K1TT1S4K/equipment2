<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Equipment;
use App\Models\Equipment_document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Arr;

class DocumentController extends Controller
{
    // หน้าแสดงข้อมูลเอกสาร
    public function index()
    {
        $documents = Document::orderBy('created_at', 'desc')->paginate(10); // แสดงเอกสารทั้งหมดในหน้าแรก โดยแบ่งหน้า 10 รายการต่อหน้า
        return view('page.documents.show', compact('documents'));
    }

    // หน้าเพิ่มข้อมูลเอกสาร
    public function create()
    {
        return view('page.documents.add');
    }

    // หน้าแก้ไขเอกสาร
    public function edit($id)
    {
        $document = Document::findOrFail($id); // ค้นหาเอกสารตาม ID ที่ส่งมา
        $equipments_documents = Equipment_document::where('document_id', $document->id)->orderByDesc('created_at')->paginate(10);
        $equipment_ids = $equipments_documents->pluck('equipment_id');
        $equipments = Equipment::all();

        // dd($document->id, $equipments_documents, $equipments);

        return view('page.documents.edit', compact('document', 'equipments_documents', 'equipments')); // ส่งเอกสารไปยัง view
    }

    // หน้ากู้ข้อมูลเอกสาร
    public function trash()
    {
        $documents = Document::onlyTrashed()->paginate(10); // ค้นหาเอกสารที่ถูกลบ
        return view('page.documents.trash', compact('documents')); // ส่งเอกสารที่ถูกลบไปยัง view
    }

    // ฟังก์ชันค้นหาเอกสาร
    public function search(Request $request)
    {
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

        $documents = Document::when($search, function ($query, $search) {
            if ($search)
                $query->where(function ($q) use ($search) {
                    $q->where('document_type', 'like', "%{$search}%")
                        ->orWhere('stored_name', 'like', "%{$search}%")
                        ->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhereRaw("
                    CONCAT(DAY(date), ' ', 
                        CASE MONTH(date)
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
                        YEAR(date) + 543,
                        ' ',
                        DATE_FORMAT(date, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
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
                ) LIKE ?", ["%{$search}%"]);
                });
        })
            ->when($documentType, function ($query, $documentType) {
                return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // สำคัญ: เก็บ query string เอาไว้
        $documents->appends($request->all());

        return view('page.documents.show', compact('documents')); // ส่งผลลัพธ์ที่ค้นพบไปยัง view
    }

    // ฟังก์ชันเพิ่มข้อมูลเอกสาร
    public function store(Request $request)
    {
    //     $file = $request->file('document');
    //     $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

    // dd($request);

         $request->validate([ // ตรวจสอบความถูกต้องของข้อมูล
            'document_type' => 'required|string', // ประเภทเอกสาร
            'date' => 'required|date', // วันที่
            'document' => 'required|file|mimes:pdf' // ขนาดไฟล์ไม่เกิน 2MB
        ]);

        $file = $request->file('document'); // รับไฟล์เอกสาร
        // $filePath = $file->store('documents', 'public'); // เก็บไฟล์ใน storage/app/public/documents
        $originalName = $file->getClientOriginalName(); // ดึงชื่อไฟล์เดิม
        // $filePath = $file->storeAs('documents', $originalName, 'public'); // เก็บด้วยชื่อเดิม
        $filePath = $file->store('', 'public'); 

        Document::create([ // สร้างเอกสารใหม่ในฐานข้อมูล
            // dd('99'),
            'original_name' => $originalName,
            'stored_name' => $filePath,
            'document_type' => $request->document_type, // ประเภทเอกสาร
            'date' => $request->date, // วันที่
            // 'path' => $filePath, // ที่อยู่ไฟล์ใน storage
            // 'path' => 'documents/' . $originalName, // ที่อยู่ไฟล์ใน storage

        ]);

        $document = Document::latest('id')->first();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'เพิ่มข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($document)
            ->withProperties($document->only(['original_name', 'stored_name', 'document_type', 'date']))
            ->log('เอกสาร');


        return redirect($request->input('redirect_to', route('document.index')))->with('success', 'เพิ่มเอกสารสำเร็จ'); // ส่งข้อความสำเร็จไปยังหน้าเอกสาร
    }

    // ฟังก์ชันแก้ไขเอกสาร
    public function update(Request $request, $id)
    {
        $request->validate([
            'document_type' => 'required|string',
            'date' => 'required|date',
            'newFile' => 'nullable|file|mimes:pdf',
        ]);

        $document = Document::findOrFail($id);

        $oldValues = $document->toArray();

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

        $newValues = $document->toArray();

        activity()
            ->tap(function ($activity) {
                $activity->menu = 'แก้ไขข้อมูล';
            })
            ->useLog(auth()->user()->full_name)
            ->performedOn($document)
            ->withProperties([
                'ข้อมูลก่อนแก้' => Arr::only($oldValues, ['original_name', 'stored_name', 'document_type', 'date']),
                'ข้อมูลหลังแก้' => Arr::only($newValues, ['original_name', 'stored_name', 'document_type', 'date'])
            ])
            ->log('เอกสาร');

        return redirect($request->input('redirect_to', route('document.index')))->with('success', 'อัปเดตเอกสารเรียบร้อยแล้ว');
    }

    // ฟังก์ชันกู้คืนข้อมูลเอกสาร
    public function restoreMultiple(Request $request)
    {
        $ids = explode(',', $request->input('selected_documents', ''));
        // dd($ids);
        $documents = Document::onlyTrashed()->whereIn('id', $ids)->get();

        LogBatch::startBatch();
        foreach ($documents as $document) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'กู้คืนข้อมูล';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($document)
                ->withProperties($document->only(['original_name', 'stored_name', 'document_type', 'date']))
                ->log('เอกสาร');
        }
        LogBatch::endBatch();

        Document::onlyTrashed()->whereIn('id', $ids)->restore();
        return redirect($request->input('redirect_to', route('document.trash')))->with('success', 'กู้คืนเอกสารที่เลือกเรียบร้อยแล้ว');
    }

    // ฟังก์ชันลบข้อมูลเอกสารแบบซอฟต์
    public function deleteSelected(Request $request)
    {
        $documentIds = $request->input('selected_documents');

        if ($documentIds) {
            $documents = Document::whereIn('id', $documentIds)->get();

            LogBatch::startBatch();
            foreach ($documents as $document) {
                activity()
                    ->tap(function ($activity) {
                        $activity->menu = 'ลบข้อมูลแบบซอฟต์';
                    })
                    ->useLog(auth()->user()->full_name)
                    ->performedOn($document)
                    ->withProperties($document->only(['original_name', 'stored_name', 'document_type', 'date']))
                    ->log('เอกสาร');
            }
            LogBatch::endBatch();

            Document::whereIn('id', $documentIds)->delete(); // <-- ใช้ SoftDelete ถ้าเปิดใช้งาน
            return redirect()->route('document.index')->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
        }

        return redirect($request->input('redirect_to'), route('document.index'))->with('error', 'กรุณาเลือกเอกสาร');
    }

    // ฟังก์ชันลบข้อมูลเอกสารถาวร
    public function forceDeleteSelected(Request $request)
    {
        $ids = explode(',', $request->input('selected_documents', ''));

        $documents = Document::onlyTrashed()->whereIn('id', $ids)->get();

        LogBatch::startBatch();
        foreach ($documents as $document) {
            activity()
                ->tap(function ($activity) {
                    $activity->menu = 'ลบข้อมูลถาวร';
                })
                ->useLog(auth()->user()->full_name)
                ->performedOn($document)
                ->withProperties($document->only(['original_name', 'stored_name', 'document_type', 'date']))
                ->log('เอกสาร');
        }
        LogBatch::endBatch();

        Document::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        return redirect($request->input('redirect_to', route('document.trash')))->with('success', 'ลบถาวรเรียบร้อยแล้ว');
    }

    // ฟังก์ชันค้าหาข้อมูลเอกสารที่ถูกลบแบบซอฟต์
    public function searchTrash(Request $request)
    {
        $search = $request->input('query'); // ค้นหาจากชื่อไฟล์
        $documentType = $request->input('document_type'); // ค้นหาจากประเภทเอกสาร

        $documents = Document::onlyTrashed()->when($search, function ($query, $search) {
            if ($search)
                $query->where(function ($q) use ($search) {
                    $q->where('document_type', 'like', "%{$search}%")
                        ->orWhere('stored_name', 'like', "%{$search}%")
                        ->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhereRaw("
                    CONCAT(DAY(date), ' ', 
                        CASE MONTH(date)
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
                        YEAR(date) + 543,
                        ' ',
                        DATE_FORMAT(date, '%H:%i:%s')
                    ) LIKE ?", ["%{$search}%"])
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
                ) LIKE ?", ["%{$search}%"]);
                });
        })
            ->when($documentType, function ($query, $documentType) {
                return $query->where('document_type', $documentType); // กรองตามประเภทเอกสาร
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        // สำคัญ: เก็บ query string เอาไว้
        $documents->appends($request->all());

        return view('page.documents.trash', compact('documents'));
    }
}
