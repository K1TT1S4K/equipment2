<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Document;
use App\Models\Equipment_document;
use Illuminate\Http\Request;

class EquipmentDocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required',
            'document_id' => 'required',
            'amount' => 'required'
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);
        $document = Document::findOrFail($request->document_id);

        Equipment_document::create([
            'equipment_id' => $request->equipment_id,
            'document_id' => $request->document_id,
            'amount' => $request->amount
        ]);
        return redirect()->route('document.edit', ['id' => $request->document_id, 'page' => 1]);
    }

    public function deleteSelected(Request $request)
    {
        $equipments_documentsIds = $request->input('selected_equipments_documents');

        if ($equipments_documentsIds) {
            foreach ($equipments_documentsIds as $id) {
                $ed = Equipment_document::findOrFail($id);
                $equipment = $ed->equipment;
                $document = $ed->document;
            }

            Equipment_document::whereIn('id', $equipments_documentsIds)->forceDelete();
            return redirect()->route('document.edit', ['id' => $request->document_id, 'page' => 1])->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        }

        return redirect()->route('document.edit', ['id' => $request->document_id, 'page' => 1])->with('error', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
