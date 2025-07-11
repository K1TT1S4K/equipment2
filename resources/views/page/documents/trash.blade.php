<x-layouts.app>
    <div class="py-3 w-90 mx-auto">
        <h1 class="text-dark w-100 mb-2">กู้คืนเอกสาร</h1>
    </div>
    <div class="p-3 mb-4 w-90 justify-content-center mx-auto">
        <form action="{{ route('trash.search') }}" method="GET">
            <div class="row">
                <div class="col-md mb-3 mb-sm-0">
                    {{-- <label for="query" class="form-label">ค้นหา</label> --}}
                    <input type="text" id="query" name="query" class="form-control"
                        placeholder="ค้นหาเอกสาร" value="{{ request('query') }}">
                </div>

                <div class="col-md mb-3 mb-sm-0">
                    {{-- <label for="document_type" class="form-label">ประเภทเอกสาร</label> --}}
                    <select id="document_type" name="document_type" class="form-select">
                        <option value="">-- เลือกประเภทเอกสาร --</option>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            แทงจำหน่ายครุภัณฑ์</option>
                        <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                            โอนครุภัณฑ์</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-lg p-3 mb-4 bg-body w-90 justify-content-center mx-auto">
        <div class="row">
            <div class="col-4">
                <h2>รายการเอกสาร</h2>
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
                    <form id="bulk-restore-selected-form" action="{{ route('document.restoreMultiple') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_documents" id="selected_documents_json_restore_selected">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารที่เลือกใช่หรือไม่?')">
                            กู้คืนที่เลือก
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-all" style="display: none;">
                    <form id="bulk-delete-all-form" action="{{ route('document.deleteSelected') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_documents" id="selected_documents_json_delete_all">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            ลบถาวรทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-selected" style="display: none;">
                    <form id="bulk-delete-selected-form" action="{{ route('document.deleteSelected') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_documents" id="selected_documents_json_delete_selected">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารที่เลือกใช่หรือไม่?')">
                            ลบถาวรที่เลือก
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row p-3">
            <table class="table table-striped table-hover w-full">
                <thead class="text-center table-dark align-middle">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ลำดับ</th>
                        <th>ประเภทเอกสาร</th>
                        <th>วันที่ดำเนินการ</th>
                        <th>เอกสารอ้างอิง</th>
                        <th>วันที่ลบ</th>
                    </tr>
                </thead>
                <tbody class="align-middle p-3 text-center">
                    @forelse ($documents as $doc)
                        <tr>
                            <td><input type="checkbox" name="selected_documents[]" value="{{ $doc->id }}" class="document-checkbox"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $doc->document_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->date)->locale('th')->isoFormat('D MMM YYYY') }}</td>
                            <td>
                                @if ($doc->original_name)
                                    <a href="{{ asset('storage/' . $doc->original_name) }}" download>{{ $doc->original_name }}</a>
                                @else
                                    ไม่มีไฟล์แนบ
                                @endif
                            </td>
                            <td>{{ $doc->deleted_at?->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">ไม่พบเอกสารที่ตรงกับเงื่อนไขการค้นหา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('input[name="selected_documents[]"]');
                const selectAll = document.getElementById('select-all');

                selectAll.addEventListener('click', function (event) {
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
                    form.addEventListener('submit', function (e) {
                        const selectedIds = Array.from(document.querySelectorAll('.document-checkbox:checked'))
                            .map(cb => cb.value);
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
