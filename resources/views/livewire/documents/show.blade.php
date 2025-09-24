<x-layouts.app>
    <h3 class="text-dark">เอกสาร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark mt-4">
        <div class="card-body">
            <form action="{{ route('document.search') }}" method="GET">
                <div class="row">
                    <div class="col-md mb-3 mb-sm-0">
                        <label for="query" class="form-label">ค้นหา</label>
                        <input type="text" name="query" class="form-control" placeholder="ค้นหา..." value="{{ request('query') }}">
                    </div>

                    <div class="col-md mb-3 mb-sm-0">
                        <label for="document_type" class="form-label">ประเภทเอกสาร</label>
                        <select name="document_type" class="form-select">
                            <option value="">-- เลือกประเภทเอกสาร --</option>
                            <option value="ยื่นแทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>ยื่นแทงจำหน่ายครุภัณฑ์</option>
                            <option value="แทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์</option>
                            <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>โอนครุภัณฑ์</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </form>

        </div>
    </div>

    <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <div>
                <!-- ปุ่มเพิ่มข้อมูล -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addDocumentModal">เพิ่มข้อมูล</button>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr class="text-center">
                    <th class="align-middle">ลำดับ</th>
                    <th class="align-middle">ประเภทเอกสาร</th>
                    <th class="align-middle">วันที่ดำเนินการ</th>
                    <th class="align-middle">เอกสารอ้างอิง</th>
                    <th class="align-middle">วันที่แก้ไข</th>
                    <th class="align-middle">วันที่สร้าง</th>
                    <th class="align-middle">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $key => $document)
                <tr class="text-center">
                    <td class="align-middle border border-dark">{{ $key + 1 }}</td>
                    <td class="align-middle border border-dark">{{ $document->document_type }}</td>
                    <td class="align-middle border border-dark">{{ $document->date }}</td>
                    {{-- <td class="align-middle border border-dark">-</td> --}}
                    <td class="align-middle border border-dark">
                        @if ($document->path)
                            <a href="{{ asset('storage/' . $document->path) }}" download="{{ $document->path }}">
                                {{ $document->path }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="align-middle border border-dark">{{ $document->updated_at }}</td>
                    <td class="align-middle border border-dark">{{ $document->created_at }}</td>
                    <td class="align-middle border border-dark">
                        <!-- ปุ่มแก้ไข -->
                        <button class="btn btn-warning" wire:click="$emit('openEditModal', {{ $document->id }})" data-bs-toggle="modal" data-bs-target="#editDocumentModal">
                            แก้ไข
                        </button>

                        {{-- <a href="{{ route('document.update')}}" class="btn btn-warning">แก้ไข</a> --}}
                        {{-- <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDocumentModal-{{ $document->id }}">แก้ไข</button> --}}
                        {{-- @livewire('documents.edit') --}}
                        <!-- ปุ่มลบ -->
                        <form action="{{ route('document.delete', $document->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจว่าต้องการลบเอกสารนี้?')">ลบ</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @livewire('documents.add')
                @livewire('documents.edit', ['documentId' => $document->id])

            </tbody>
        </table>
        {{-- {{ $documents->links() }} --}}
    </div>
</x-layouts.app>
