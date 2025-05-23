<x-layouts.app>
    <h3 class="text-dark mb-4">เอกสาร</h3>
    <div class="card shadow-lg p-3 mb-4 bg-body">
        <form action="{{ route('document.search') }}" method="GET">
            <div class="row">
                <div class="col-md mb-3 mb-sm-0">
                    <label for="query" class="form-label">ค้นหา</label>
                    <input type="text" id="query" name="query" class="form-control"
                        placeholder="เอกสารอ้างอิง, ประเภทเอกสาร ฯลฯ" value="{{ request('query') }}">
                </div>

                <div class="col-md mb-3 mb-sm-0">
                    <label for="document_type" class="form-label">ประเภทเอกสาร</label>
                    <select id="document_type" name="document_type" class="form-select">
                        <option value="">-- เลือกประเภทเอกสาร --</option>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์"
                            {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์"
                            {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์
                        </option>
                        <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                            โอนครุภัณฑ์</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
                <a href="{{ route('document.search') }}" class="btn btn-danger ms-2">ล้างการค้นหา</a>
            </div>
        </form>
    </div>

    <div class="card shadow-lg p-3 mb-4 bg-body">
        <h3>รายการเอกสาร</h3>

        <!-- ย้าย form มาอยู่ตรงนี้ ครอบทั้งปุ่มลบและตาราง -->
        <form action="{{ route('document.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            @method('DELETE')

            <div class="row mb-3">
                <div class="col-4">
                    <div>
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                            style="display:none;">ย้ายรายการทั้งหมดไปที่ถังขยะ</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                            style="display:none;">ย้ายไปที่ถังขยะ</button>
                    </div>
                </div>
                <div class="col-4"></div>
                @can('admin-or-branch')
                    <div class="col-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div>
                                <!-- ปุ่มเพิ่มข้อมูล -->
                                <a href="{{ route('document.create') }}" class="btn btn-success mb-3">เพิ่มข้อมูล</a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <table class="table table-striped table-hover w-full">
                <thead class="text-center table-dark align-middle">
                    <tr class="text-center">
                        <th><input type="checkbox" id="select-all"></th>
                        <th class="align-middle">ลำดับ</th>
                        <th class="align-middle">ประเภทเอกสาร</th>
                        <th class="align-middle">วันที่ดำเนินการ</th>
                        <th class="align-middle">เอกสารอ้างอิง</th>
                        <th class="align-middle">วันที่แก้ไข</th>
                        <th class="align-middle">วันที่สร้าง</th>
                    </tr>
                </thead>
                <tbody class="align-middle p-3">
                    @foreach ($documents as $key => $document)
                        <tr class="text-center" style="cursor: pointer;"
                            onclick="window.location='{{ route('document.edit', $document->id) }}'">
                            <td onclick="event.stopPropagation();">
                                <input type="checkbox" class="document-checkbox" name="selected_documents[]"
                                    value="{{ $document->id }}">
                            </td>
                            <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                            <td>{{ $document->document_type }}</td>
                            @php
                                $date = \Carbon\Carbon::parse($document->date)->locale('th');
                                // $buddhistYear = $date->year + 543;
                            @endphp
                            <td class="text-center">{{ $date->isoFormat('D MMM YYYY') }}</td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                @if ($document->path)
                                    <a href="{{ asset('storage/' . $document->path) }}"
                                        download="{{ $document->path }}">{{ $document->path }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            @php
                                $updated = \Carbon\Carbon::parse($document->updated_at)->locale('th');
                                $created = \Carbon\Carbon::parse($document->created_at)->locale('th');
                            @endphp
                            <td class="text-center">{{ $updated->isoFormat('D MMM') }} {{ $updated->year + 543 }}</td>
                            <td class="text-center">{{ $created->isoFormat('D MMM') }} {{ $created->year + 543 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>

{{-- <div class="d-flex justify-content-center">
    {{$documents->links('vendor.livewire.task-paginate')}}
</div> --}}

        <script>
            document.getElementById('select-all').addEventListener('click', function(event) {
                let checkboxes = document.querySelectorAll('.document-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                });
                toggleDeleteButtons();
            });

            let checkboxes = document.querySelectorAll('.document-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleDeleteButtons);
            });

            function toggleDeleteButtons() {
                let selectedCheckboxes = document.querySelectorAll('.document-checkbox:checked');
                let deleteSelectedBtn = document.getElementById('delete-selected-btn');
                let deleteAllBtn = document.getElementById('delete-all-btn');

                if (selectedCheckboxes.length === 0) {
                    deleteSelectedBtn.style.display = 'none';
                    deleteAllBtn.style.display = 'none';
                } else if (selectedCheckboxes.length === checkboxes.length) {
                    deleteAllBtn.style.display = 'inline-block';
                    deleteSelectedBtn.style.display = 'none';
                } else {
                    deleteAllBtn.style.display = 'none';
                    deleteSelectedBtn.style.display = 'inline-block';
                }
            }
        </script>
</x-layouts.app>
