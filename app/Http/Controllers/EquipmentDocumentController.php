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
        // dd($request);
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

        if ($document->document_type == 'แทงจำหน่ายครุภัณฑ์') {
            $equipment->update([
                'status_disposal' => $equipment->status_disposal + $request->amount,
                'status_found' => $equipment->status_found - $request->amount
            ]);
        } elseif ($document->document_type == 'โอนครุภัณฑ์') {
            $equipment->update([
                'status_transfer' => $equipment->status_transfer + $request->amount,
                'status_found' => $equipment->status_found - $request->amount
            ]);
        } elseif ($document->document_type == 'ไม่พบ') {
            $equipment->update([
                'status_not_found' => $equipment->status_not_found + $request->amount,
                'status_found' => $equipment->status_found - $request->amount
            ]);
        } elseif ($document->document_type == 'ชำรุด') {
            $equipment->update([
                'status_broken' => $equipment->status_broken + $request->amount,
                'status_found' => $equipment->status_found - $request->amount
            ]);
        }

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

                // dd($ed,$ed->amount, $equipment, $equipment->status_disposal, $document, $document->document_type);
                if ($document->document_type == 'แทงจำหน่ายครุภัณฑ์') {
                    $equipment->update([
                        'status_disposal' => $equipment->status_disposal - $ed->amount,
                        'status_found' => $equipment->status_found + $ed->amount
                    ]);
                } elseif ($document->document_type == 'โอนครุภัณฑ์') {
                    $equipment->update([
                        'status_transfer' => $equipment->status_transfer - $ed->amount,
                        'status_found' => $equipment->status_found + $ed->amount
                    ]);
                } elseif ($document->document_type == 'ไม่พบ') {
                    $equipment->update([
                        'status_not_found' => $equipment->status_not_found - $ed->amount,
                        'status_found' => $equipment->status_found + $ed->amount
                    ]);
                } elseif ($document->document_type == 'ชำรุด') {
                    $equipment->update([
                        'status_broken' => $equipment->status_broken - $ed->amount,
                        'status_found' => $equipment->status_found + $ed->amount
                    ]);
                }
            }

            Equipment_document::whereIn('id', $equipments_documentsIds)->forceDelete();
            return redirect()->route('document.edit', ['id' => $request->document_id, 'page' => 1])->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        }

        return redirect()->route('document.edit', ['id' => $request->document_id, 'page' => 1])->with('error', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
