<x-layouts.app>
    <h3 class="text-dark mb-4">กู้คืนเอกสาร</h3>
    <form action="{{ route('trash.search') }}" method="GET" class="mb-3">
        <div class="d-flex">

            {{-- <label for="query" class="form-label">ค้นหา</label> --}}
            <input type="text" name="search" class="form-control shadow-lg p-2 mb-3 rounded" placeholder="ค้นหาเอกสาร"
                value="{{ request('search') }}">
            {{-- <label for="document_type" class="form-label">ประเภทเอกสาร</label> --}}
            <select id="document_type" name="document_type" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
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


            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            {{-- <a href="{{ route('document.search') }}" class="btn btn-danger ms-2">ล้างการค้นหา</a> --}}

        </div>
    </form>
    {{-- <div class="card shadow-lg p-3 mb-4 bg-body">
        <form action="{{ route('trash.search') }}" method="GET">
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
                            {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            แทงจำหน่ายครุภัณฑ์</option>
                        <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                            โอนครุภัณฑ์</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
                <a href="{{ route('document.trash') }}" class="btn btn-danger ms-2">ล้างการค้นหา</a>
            </div>
        </form>
    </div> --}}

    <div class="card shadow-lg p-3 bg-body">
        <div class="row mb-2">
            <div class="col-4">
                <h3>รายการเอกสาร</h3>
            </div>
            <div class="col-8 d-flex justify-content-end gap-2">
                <!-- ปุ่ม Bulk Actions (กู้คืน, ลบถาวร) ซ่อนไว้ก่อน -->
                <div id="bulk-restore-all" style="display: none;">
                    <form id="bulk-restore-form" action="{{ route('document.restoreMultiple') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_documents" id="selected_documents_json_restore">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            กู้คืนทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-restore-selected" style="display: none;">
                    <form id="bulk-restore-selected-form" action="{{ route('document.restoreMultiple') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="selected_documents" id="selected_documents_json_restore_selected">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารที่เลือกใช่หรือไม่?')">
                            กู้คืนที่เลือก
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-all" style="display: none;">
                    <form id="bulk-delete-all-form" action="{{ route('document.forceDeleteSelected') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="selected_documents" id="selected_documents_json_delete_all">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            ลบถาวรทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-selected" style="display: none;">
                    <form id="bulk-delete-selected-form" action="{{ route('document.forceDeleteSelected') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="selected_documents" id="selected_documents_json_delete_selected">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารที่เลือกใช่หรือไม่?')">
                            ลบถาวรที่เลือก
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <table class="table table-hover w-full">
            <thead class="text-center table-dark align-middle">
                <tr class="text-center">
                    <th><input type="checkbox" id="select-all"></th>
                    <th class="align-middle">ลำดับ</th>
                    <th class="align-middle">ประเภทเอกสาร</th>
                    <th class="align-middle">วันที่ดำเนินการ</th>
                    <th class="align-middle">เอกสารอ้างอิง</th>
                    <th class="align-middle">วันที่ลบ</th>
                    {{-- <th class="align-middle">วันที่สร้าง</th> --}}
                </tr>
            </thead>
            <tbody class="align-middle p-3">
                @forelse ($documents as $key => $document)
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
                            @if ($document->stored_name)
                                <a href="{{ asset('storage/' . $document->stored_name) }}"
                                    download="{{ $document->original_name }}">{{ $document->original_name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        @php
                            $updated = \Carbon\Carbon::parse($document->updated_at)->locale('th');
                            $created = \Carbon\Carbon::parse($document->created_at)->locale('th');
                            $deleted = \Carbon\Carbon::parse($document->deleted_at)->locale('th');
                        @endphp
                        <td class="text-center">{{ $deleted->isoFormat('D MMM') }} {{ $deleted->year + 543 }}
                            {{ $deleted->format('H:i:s') }}</td>
                        {{-- <td class="text-center">{{ $created->isoFormat('D MMM') }} {{ $created->year + 543 }}
                                {{ $created->format('H:i:s') }}</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{-- ไว้ดูค่าเพื่อ debug --}}
            {{-- <pre>
{{ print_r(request()->all(), true) }}
{{ $documents->url(2) }}
</pre> --}}
            {{ $documents->links() }}
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('input[name="selected_documents[]"]');
                const selectAll = document.getElementById('select-all');

                selectAll.addEventListener('click', function(event) {
                    checkboxes.forEach(cb => cb.checked = event.target.checked);
                    toggleButtons();
                });

                checkboxes.forEach(cb => cb.addEventListener('change', toggleButtons));

                function toggleButtons() {
                    const selected = Array.from(checkboxes).filter(cb => cb.checked).length;

                    const restoreAll = document.getElementById('bulk-restore-all');
                    const restoreSelected = document.getElementById('bulk-restore-selected');
                    const deleteAll = document.getElementById('bulk-delete-all');
                    const deleteSelected = document.getElementById('bulk-delete-selected');

                    if (selected === checkboxes.length && selected > 0) {
                        restoreAll.style.display = 'block';
                        restoreSelected.style.display = 'none';
                        deleteAll.style.display = 'block';
                        deleteSelected.style.display = 'none';
                    } else if (selected > 0) {
                        restoreAll.style.display = 'none';
                        restoreSelected.style.display = 'block';
                        deleteAll.style.display = 'none';
                        deleteSelected.style.display = 'block';
                    } else {
                        restoreAll.style.display = 'none';
                        restoreSelected.style.display = 'none';
                        deleteAll.style.display = 'none';
                        deleteSelected.style.display = 'none';
                    }
                }

                function bindFormSubmission(formId, hiddenInputId) {
                    const form = document.getElementById(formId);
                    const hiddenInput = document.getElementById(hiddenInputId);
                    form.addEventListener('submit', function(e) {
                        const selectedIds = Array.from(document.querySelectorAll('.document-checkbox:checked'))
                            .map(cb => cb.value);
                            //  console.log("Selected IDs:", selectedIds); // ✅ debug
                        if (selectedIds.length === 0) {
                            e.preventDefault();
                            alert('กรุณาเลือกเอกสารที่ต้องการ');
                            return false;
                        }
                        hiddenInput.value = selectedIds.join(',');
                    });
                }

                bindFormSubmission('bulk-restore-form', 'selected_documents_json_restore');
                bindFormSubmission('bulk-restore-selected-form', 'selected_documents_json_restore_selected');
                bindFormSubmission('bulk-delete-all-form', 'selected_documents_json_delete_all');
                bindFormSubmission('bulk-delete-selected-form', 'selected_documents_json_delete_selected');
            });
        </script>
    </div>
</x-layouts.app>
