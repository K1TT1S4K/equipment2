<div>
    <h3 class="text-dark">เอกสาร</h3>

    <!-- ฟอร์มค้นหา -->
    <div class="card p-3 mb-4">
        <div class="row">
            <div class="col-md">
                <input type="text" wire:model="query" class="form-control" placeholder="ค้นหา...">
            </div>
            <div class="col-md">
                <select wire:model="document_type" class="form-select">
                    <option value="">-- เลือกประเภทเอกสาร --</option>
                    <option value="ยื่นแทงจำหน่ายครุภัณฑ์">ยื่นแทงจำหน่ายครุภัณฑ์</option>
                    <option value="แทงจำหน่ายครุภัณฑ์">แทงจำหน่ายครุภัณฑ์</option>
                    <option value="โอนครุภัณฑ์">โอนครุภัณฑ์</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ปุ่มเพิ่มเอกสาร -->
    <button class="btn btn-success mb-3" wire:click="openCreateForm">เพิ่มข้อมูล</button>

    <!-- ตารางข้อมูล -->
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>ประเภทเอกสาร</th>
                <th>วันที่ดำเนินการ</th>
                <th>เอกสารอ้างอิง</th>
                <th>วันที่สร้าง</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $key => $document)
            <tr>
                <td>{{ $loop->key + 1 }}</td>
                <td>{{ $document->document_type }}</td>
                <td>{{ $document->date }}</td>
                <td>
                    @if ($document->path)
                        <a href="{{ asset('storage/' . $document->path) }}" download>ดาวน์โหลด</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <button class="btn btn-warning" wire:click="openEditForm({{ $document->id }})">แก้ไข</button>
                    <button class="btn btn-danger" wire:click="delete({{ $document->id }})" onclick="return confirm('ยืนยันการลบ?')">ลบ</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $documents->links() }}
</div>
