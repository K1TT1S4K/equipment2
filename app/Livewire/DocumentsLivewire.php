<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentsLivewire extends Component
{
    use WithPagination, WithFileUploads;

    public $query;
    public $document_type;
    public $document_id, $date, $path, $new_path;
    public $isEdit = false;
    public $showForm = false;


    protected $rules = [
        'document_type' => 'required',
        'date' => 'required|date',
        'new_path' => 'nullable|file|max:10240'
    ];

    protected $listeners = ['refreshList' => '$refresh'];

    // ลบ `public $documents;` ออก เพราะ `$documents` มาจาก render() แล้ว
    public function render()
    {
        $documents = Document::query()
            ->when($this->query, function ($query) {
                $query->where('document_type', 'like', '%' . $this->query . '%');
            })
            ->when($this->document_type, function ($query) {
                $query->where('document_type', $this->document_type);
            })
            ->paginate(10);

        return view('livewire.documents.show', compact('documents'));
    }

    public function closeForm()
    {
        $this->resetInput();
        $this->showForm = false;
    }

    // public function openCreateForm()
    // {
    //     $this->emit('openCreateForm');
    // }

    public function openCreateForm()
    {
        $this->resetInput();
        $this->showForm = true;
        $this->isEdit = false;  // กำหนดให้เป็นโหมดสร้างใหม่
    }

    // public function openEditForm($id)
    // {
    //     $this->emit('openEditForm', $id);
    // }

    public function openEditForm($id)
    {
        $doc = Document::findOrFail($id);
        $this->document_id = $doc->id;
        $this->document_type = $doc->document_type;
        $this->date = $doc->date;
        $this->path = $doc->path;
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function confirmDelete($id)
    {
        $this->document_id = $id;
        $this->emit('showDeleteConfirmation');
    }

    public function delete()
    {
        $doc = Document::findOrFail($this->document_id);
        if ($doc->path) Storage::delete('public/' . $doc->path);
        $doc->delete();

        session()->flash('message', 'ลบเอกสารเรียบร้อย!');
        $this->emit('refreshList');
    }

    // public function delete($id)
    // {
    //     Document::findOrFail($id)->delete();
    //     session()->flash('message', 'Document deleted successfully.');
    // }

    public function save()
    {
        $this->validate();

        try {
            $path = $this->new_path ? $this->new_path->store('documents', 'public') : null;

            Document::create([
                'document_type' => $this->document_type,
                'date' => $this->date,
                'path' => $path,
            ]);

            session()->flash('message', 'เพิ่มเอกสารเรียบร้อย!');
        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์: ' . $e->getMessage());
        }

        $this->resetInput();
        $this->emit('refreshList');
    }

    public function update()
    {
        $this->validate();

        $doc = Document::findOrFail($this->document_id);
        if ($this->new_path) {
            if ($doc->path) Storage::delete('public/' . $doc->path);
            $doc->path = $this->new_path->store('documents', 'public');
        }

        $doc->update([
            'document_type' => $this->document_type,
            'date' => $this->date,
            'path' => $doc->path,
        ]);

        session()->flash('message', 'อัปเดตเอกสารเรียบร้อย!');
        $this->resetInput();
        $this->emit('refreshList');
    }

    // public function delete($id)
    // {
    //     $doc = Document::findOrFail($id);
    //     if ($doc->path) Storage::delete('public/' . $doc->path);
    //     $doc->delete();

    //     session()->flash('message', 'ลบเอกสารเรียบร้อย!');
    //     $this->emit('refreshList');
    // }

    private function resetInput()
    {
        $this->document_type = '';
        $this->date = '';
        $this->new_path = null;
        $this->document_id = null;
    }
}
