<x-layouts.app>
    <div class="py-3 w-90 mx-auto">
        <h1 class="text-dark w-100 mb-2">ผู้ใช้ที่ถูกลบ</h1>
    </div>
    {{-- <h1 class="text-dark mb-4">ผู้ใช้ที่ถูกลบ</h1> --}}

    <form method="GET" action="{{ route('user.trashsearch') }}" class="mb-3 w-90 justify-content-center mx-auto">
        <div class="d-flex">
            <input type="text" name="search" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหาบัญชีผู้ใช้..." value="{{ request()->get('search') }}">
            <select name="user_type" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
                <option value="">-- เลือกระดับผู้ใช้ --</option>
                <option value="ผู้ดูแลระบบ" {{ request()->get('user_type') == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>
                    ผู้ดูแลระบบ</option>
                <option value="เจ้าหน้าที่สาขา"
                    {{ request()->get('user_type') == 'เจ้าหน้าที่สาขา' ? 'selected' : '' }}>เจ้าหน้าที่สาขา</option>
                <option value="ผู้ปฏิบัติงานบริหาร"
                    {{ request()->get('user_type') == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร
                </option>
                <option value="อาจารย์" {{ request()->get('user_type') == 'อาจารย์' ? 'selected' : '' }}>อาจารย์
                </option>
            </select>
            {{-- <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button> --}}
        </div>
    </form>

    <div class="card bg-body p-3 mb-4 w-90 justify-content-center mx-auto shadow-lg">
        <div class="row mb-1">
            <div class="col-4">
                <h2>รายการผู้ใช้</h2>
            </div>
            <div class="col-2 text-center">
                <p class="text-muted" id="selected-count-info" style="font-size: 0.9rem;"></p>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="min-height: 50px;">
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
                </div>
            </div>
        </div>

        <table class="mint-table w-100">
            <thead class="text-center align-middle">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ลำดับ</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>สถานะ</th>
                    <th>วันที่ลบ</th>
                </tr>
            </thead>
            <tbody id="user-list" class="align-middle text-center">
                @include('page.users.partials.trash_rows', ['users' => $users])
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>

    <!-- Hidden Forms -->
    <form id="bulk-restore-form" action="{{ route('user.restoreAll') }}" method="POST">@csrf
        <input type="hidden" name="selected_users" id="selected_users_json_restore">
    </form>
    <form id="bulk-restore-selected-form" action="{{ route('user.restoreSelected') }}" method="POST">@csrf
        <input type="hidden" name="selected_users" id="selected_users_json_restore_selected">
    </form>
    <form id="bulk-delete-all-form" action="{{ route('user.deleteSelected') }}" method="POST">@csrf
        <input type="hidden" name="selected_users" id="selected_users_json_delete_all">
    </form>
    <form id="bulk-delete-selected-form" action="{{ route('user.deleteSelected') }}" method="POST">@csrf
        <input type="hidden" name="selected_users" id="selected_users_json_delete_selected">
    </form>
</x-layouts.app>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const userTypeSelect = document.getElementById('user_type');
    const userList = document.getElementById('user-list');

    async function fetchUsers() {
        const search = encodeURIComponent(searchInput.value);
        const userType = encodeURIComponent(userTypeSelect.value);
        const url = `{{ route('user.trashsearch') }}?search=${search}&user_type=${userType}`;

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Network error');
            const html = await response.text();
            userList.innerHTML = html;
            // ถ้ามี pagination ต้อง bind event ใหม่ หรือพัฒนาเพิ่มต่อ
        } catch (error) {
            console.error(error);
            userList.innerHTML = `<tr><td colspan="7" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>`;
        }
    }

    let debounceTimer;
    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchUsers, 300);
    }

    searchInput.addEventListener('input', debounceFetch);
    userTypeSelect.addEventListener('change', fetchUsers);
});

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

    function getSelectedUserIds() {
        return Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    }

    function updateSelectedCount() {
        const selectedCount = getSelectedUserIds().length;
        const info = document.getElementById('selected-count-info');
        info.textContent = selectedCount > 0 ? `เลือกผู้ใช้แล้ว ${selectedCount} รายการ` : '';
    }

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
        document.getElementById('bulk-delete-selected').style.display = selected.length > 0 ? 'inline-block' : 'none';
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
</script>
