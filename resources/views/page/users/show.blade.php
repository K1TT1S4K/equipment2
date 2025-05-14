<x-layouts.app>
    <h3 class="text-dark mb-4">จัดการบัญชีผู้ใช้</h3>

    <form method="GET" action="{{ route('user.search') }}" class="mb-3">
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
        <h3>รายการเอกสาร</h3>

        <!-- ย้าย form มาอยู่ตรงนี้ ครอบทั้งปุ่มลบและตาราง -->
        <form action="{{ route('user.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            @method('DELETE')

            <div class="row">
                <div class="col-4">
                    <div>
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn" style="display:none;">ลบรายการทั้งหมด</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn" style="display:none;">ลบรายการที่เลือก</button>
                    </div>
                </div>
                <div class="col-4"></div>
                <div class="col-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div>
                            <a href="{{ route('user.create') }}" class="btn btn-success mb-3">เพิ่มผู้ใช้</a>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered mt-3 table-hover w-full">
                <thead class="table-dark text-white text-center border border-dark">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ระดับผู้ใช้</th>
                        <th>อีเมล</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                    <tr class="align-middle border border-secondary-subtle" style="cursor: pointer;" onclick="window.location='{{ route('user.edit', $user->id) }}'">
                        <td class="text-center" style="width: 3%" onclick="event.stopPropagation();">
                            <input type="checkbox" class="document-checkbox" name="selected_users[]" value="{{ $user->id }}">
                        </td>
                        <td class="text-center" style="width: 3%">{{ $key + 1 }}</td>
                        <td style="width: 10%">{{ $user->username }}</td>
                        <td class="p-3" style="width: 20%">{{ $user->prefix->name }} {{ $user->firstname }} {{ $user->lastname }}</td>
                        <td class="text-center" style="width: 10%">{{ $user->user_type }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        {{ $users->links() }}
    </div>

    <script>
        // ฟังก์ชันในการเลือกทั้งหมด
        document.getElementById('select-all').addEventListener('click', function(event) {
            let checkboxes = document.querySelectorAll('.document-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
            toggleDeleteButtons();
        });

        // การเพิ่มเหตุการณ์ให้กับ checkboxes แต่ละตัว
        let checkboxes = document.querySelectorAll('.document-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButtons);
        });

        // ฟังก์ชันในการแสดงปุ่มลบที่เหมาะสม
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
