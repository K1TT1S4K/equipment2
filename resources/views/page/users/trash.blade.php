<x-layouts.app>
    <h3 class="text-dark mb-4">กู้คืนผู้ใช้</h3>

    <form method="GET" action="{{ route('user.trashsearch') }}" class="mb-3">
        <div class="d-flex">
            <input type="text" name="search" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหาจากข้อมูลบัญชีผู้ใช้" value="{{ request()->get('search') }}">
            <select name="user_type" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
                <option value="">-- เลือกระดับผู้ใช้ --</option>
                <option value="ผู้ดูแลระบบ" {{ request()->get('user_type') == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>
                    ผู้ดูแลระบบ</option>
                <option value="เจ้าหน้าที่พ้สดุ"
                    {{ request()->get('user_type') == 'เจ้าหน้าที่พ้สดุ' ? 'selected' : '' }}>เจ้าหน้าที่พ้สดุ</option>
                <option value="ผู้ปฏิบัติงานบริหาร"
                    {{ request()->get('user_type') == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร
                </option>
                <option value="ผู้ใช้งานครุภัณฑ์" {{ request()->get('user_type') == 'ผู้ใช้งานครุภัณฑ์' ? 'selected' : '' }}>ผู้ใช้งานครุภัณฑ์
                </option>
            </select>
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            <button type="button" class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded"
                onclick="window.location='{{ route('user.trashsearch') }}'" style="width: 18%">ล้างการค้นหา</button>
        </div>
    </form>

    <div class="card shadow-lg p-3 bg-body">
        <div class="row mb-2">
            <div class="col-4">
                <h3>รายการผู้ใช้</h3>
            </div>
            {{-- <div class="col-4 text-center">
                <p class="text-muted" id="selected-count-info" style="font-size: 0.9rem;"></p>
            </div> --}}
            {{-- <div class="col-8 d-flex justify-content-end gap-2">
                <div id="bulk-restore-all" style="display: none;">
                    <button type="submit" form="bulk-restore-form" class="btn btn-warning"
                        onclick="return confirm('คุณต้องการกู้คืนผู้ใช้ทั้งหมดที่เลือกใช่หรือไม่?')">
                        กู้คืนทั้งหมด
                    </button>
                </div>

                <div id="bulk-restore-selected" style="display: none;">
                    <button type="submit" form="bulk-restore-selected-form" class="btn btn-warning"
                        onclick="return confirm('คุณต้องการกู้คืนผู้ใช้ที่เลือกใช่หรือไม่?')">
                        กู้คืนที่เลือก
                    </button>
                </div>

                <div id="bulk-delete-all" style="display: none;">
                    <button type="submit" form="bulk-delete-all-form" class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบถาวรผู้ใช้ทั้งหมดที่เลือกใช่หรือไม่?')">
                        ลบถาวรทั้งหมด
                    </button>
                </div>

                <div id="bulk-delete-selected" style="display: none;">
                    <button type="submit" form="bulk-delete-selected-form" class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบถาวรผู้ใช้ที่เลือกใช่หรือไม่?')">
                        ลบถาวรที่เลือก
                    </button>
                </div>
            </div> --}}

            <div class="col-8 d-flex justify-content-end gap-2">
                <!-- ปุ่ม Bulk Actions (กู้คืน, ลบถาวร) ซ่อนไว้ก่อน -->
                <div id="bulk-restore-all" style="display: none;">
                    <form id="bulk-restore-form" action="{{ route('user.restoreSelected') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_users" id="selected_users_json_restore">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            กู้คืนทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-restore-selected" style="display: none;">
                    <form id="bulk-restore-selected-form" action="{{ route('user.restoreSelected') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_users" id="selected_users_json_restore_selected">
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารที่เลือกใช่หรือไม่?')">
                            กู้คืนที่เลือก
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-all" style="display: none;">
                    <form id="bulk-delete-all-form" action="{{ route('user.forceDeleteSelected') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_users" id="selected_users_json_delete_all">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            ลบถาวรทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-selected" style="display: none;">
                    <form id="bulk-delete-selected-form" action="{{ route('user.forceDeleteSelected') }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_users" id="selected_users_json_delete_selected">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารที่เลือกใช่หรือไม่?')">
                            ลบถาวรที่เลือก
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row p-3">
            <table class="table table-hover w-full">
                <thead class="text-center table-dark align-middle">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ลำดับ</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        {{-- <th>อีเมล</th> --}}
                        <th>ระดับผู้ใช้</th>
                        <th>วันที่ลบ</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    @forelse ($users as $user)
                        <tr>
                            <td onclick="event.stopPropagation();">
                                <input type="checkbox" class="document-checkbox" name="selected_users[]"
                                    value="{{ $user->id }}">
                            </td>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->prefix->name }} {{ $user->firstname }} {{ $user->lastname }}</td>
                            {{-- <td>{{ $user->email }}</td> --}}
                            <td>{{ $user->user_type }}</td>
                            @php
                                $deleted = \Carbon\Carbon::parse($user->deleted_at)->locale('th');
                            @endphp
                            <td>{{ $deleted->isoFormat('D MMM') }} {{ $deleted->year + 543 }}
                                {{ $deleted->format('H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">ไม่พบข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</x-layouts.app>

{{-- <script>
    document.getElementById('select-all').addEventListener('click', function(event) {
        let checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
        toggleActionButtons();
    });

    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleActionButtons);
    });

    function updateHiddenInputs() {
        const selected = getSelectedUserIds();
        document.getElementById('selected_users_json_restore').value = JSON.stringify(selected);
        document.getElementById('selected_users_json_restore_selected').value = JSON.stringify(selected);
        document.getElementById('selected_users_json_delete_all').value = JSON.stringify(selected);
        document.getElementById('selected_users_json_delete_selected').value = JSON.stringify(selected);
    }

    function toggleActionButtons() {
        const selected = document.querySelectorAll('.user-checkbox:checked');
        const all = document.querySelectorAll('.user-checkbox');

        document.getElementById('bulk-restore-selected').style.display = selected.length > 0 && selected.length < all
            .length ? 'inline-block' : 'none';
        document.getElementById('bulk-restore-all').style.display = selected.length === all.length && all.length > 0 ?
            'inline-block' : 'none';
        // document.getElementById('bulk-delete-selected').style.display = selected.length > 0  'inline-block' : 'none';
        document.getElementById('bulk-delete-selected').style.display = selected.length > 0 && selected.length < all
            .length ? 'inline-block' : 'none';
        document.getElementById('bulk-delete-all').style.display = selected.length === all.length && all.length > 0 ?
            'inline-block' : 'none';

        updateSelectedCount();
    }

    // Update hidden inputs before each form submits
    ['bulk-restore-form', 'bulk-restore-selected-form', 'bulk-delete-all-form', 'bulk-delete-selected-form'].forEach(
        id => {
            document.getElementById(id).addEventListener('submit', function() {
                updateHiddenInputs();
            });
        });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
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

        bindFormSubmission('bulk-restore-form', 'selected_users_json_restore');
        bindFormSubmission('bulk-restore-selected-form', 'selected_users_json_restore_selected');
        bindFormSubmission('bulk-delete-all-form', 'selected_users_json_delete_all');
        bindFormSubmission('bulk-delete-selected-form', 'selected_users_json_delete_selected');
    });
</script>
