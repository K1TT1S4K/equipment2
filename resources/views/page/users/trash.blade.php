<x-layouts.app>
    <h3 class="text-dark mb-4">ผู้ใช้ที่ถูกลบ</h3>

    <form method="GET" action="{{ route('user.trashsearch') }}" class="mb-3">
        <div class="d-flex">
            <input type="text" name="search" class="form-control shadow-lg p-2 mb-3 rounded" placeholder="ค้นหาบัญชีผู้ใช้..." value="{{ request()->get('search') }}">
            <select name="user_type" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
                <option value="">-- เลือกระดับผู้ใช้ --</option>
                <option value="ผู้ดูแลระบบ" {{ request()->get('user_type') == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                <option value="เจ้าหน้าที่สาขา" {{ request()->get('user_type') == 'เจ้าหน้าที่สาขา' ? 'selected' : '' }}>เจ้าหน้าที่สาขา</option>
                <option value="ผู้ปฏิบัติงานบริหาร" {{ request()->get('user_type') == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร</option>
                <option value="อาจารย์" {{ request()->get('user_type') == 'อาจารย์' ? 'selected' : '' }}>อาจารย์</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
        </div>
    </form>

    <div class="card shadow-lg p-3 mb-4 bg-body">
        <form action="{{ route('user.restoreSelected') }}" method="POST" id="restore-form">
            @csrf
            <div class="row mb-3">
                <div class="col-4"><h4>รายการผู้ใช้</h4></div>
                <div class="col-4"></div>
                <div class="col-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div id="bulk-restore-all" style="display: none;">
                            <form id="bulk-restore-form" action="{{ route('user.restoreAll') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_users" id="selected_users_json_restore">
                                <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('คุณต้องการกู้คืนผู้ใช้ทั้งหมดที่เลือกใช่หรือไม่?')">
                                    กู้คืนทั้งหมด
                                </button>
                            </form>
                        </div>

                        <div id="bulk-restore-selected" style="display: none;">
                            <form id="bulk-restore-selected-form" action="{{ route('user.restoreSelected') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_users" id="selected_users_json_restore_selected">
                                <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('คุณต้องการกู้คืนผู้ใช้ที่เลือกใช่หรือไม่?')">
                                    กู้คืนที่เลือก
                                </button>
                            </form>
                        </div>

                        <div id="bulk-delete-all" style="display: none;">
                            <form id="bulk-delete-all-form" action="{{ route('user.deleteSelected') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_users" id="selected_users_json_delete_all">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('คุณต้องการลบถาวรผู้ใช้ทั้งหมดที่เลือกใช่หรือไม่?')">
                                    ลบถาวรทั้งหมด
                                </button>
                            </form>
                        </div>

                        <div id="bulk-delete-selected" style="display: none;">
                            <form id="bulk-delete-selected-form" action="{{ route('user.deleteSelected') }}" method="POST">
                                @csrf
                                <input type="hidden" name="selected_users" id="selected_users_json_delete_selected">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('คุณต้องการลบถาวรผู้ใช้ที่เลือกใช่หรือไม่?')">
                                    ลบถาวรที่เลือก
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row p-3">
            <table class="table table-striped table-hover w-full">
                <thead class="text-center table-dark align-middle">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>สถานะ</th>
                        <th>วันที่ลบ</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    @forelse ($trashedUsers as $user)
                        <tr>
                            <td><input type="checkbox" class="user-checkbox" name="selected_users[]"
                                    value="{{ $user->id }}"></td>
                            <td>{{ $loop->iteration + ($trashedUsers->currentPage() - 1) * $trashedUsers->perPage() }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->prefix->name }} {{ $user->firstname }} {{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->user_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->deleted_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">ไม่พบผู้ใช้ที่ถูกลบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $trashedUsers->links() }}
</x-layouts.app>

<script>
    document.getElementById('select-all').addEventListener('click', function(event) {
    let checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = event.target.checked;
    });
    toggleActionButtons();
});

let checkboxes = document.querySelectorAll('.user-checkbox');
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', toggleActionButtons);
});

function toggleActionButtons() {
    let selected = document.querySelectorAll('.user-checkbox:checked');
    let all = document.querySelectorAll('.user-checkbox');

    // ควบคุมการแสดงปุ่มกู้คืน
    document.getElementById('bulk-restore-selected').style.display = selected.length > 0 && selected.length < all.length ? 'inline-block' : 'none';
    document.getElementById('bulk-restore-all').style.display = selected.length === all.length ? 'inline-block' : 'none';

    // ควบคุมการแสดงปุ่มลบถาวร
    document.getElementById('bulk-delete-selected').style.display = selected.length > 0 ? 'inline-block' : 'none';
    document.getElementById('bulk-delete-all').style.display = selected.length === all.length ? 'inline-block' : 'none';
}

</script>
