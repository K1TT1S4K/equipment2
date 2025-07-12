<x-layouts.app>
    <div class="py-3 w-90 mx-auto">
        <h1 class="text-dark w-100 mb-2">จัดการบัญชีผู้ใช้</h1>
    </div>
    {{-- <h1 class="text-dark mb-4">จัดการบัญชีผู้ใช้</h1> --}}

    <form method="GET" action="{{ route('user.search') }}" class="mb-3 w-90 justify-content-center mx-auto">
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

    <div class="card shadow-lg p-3 mb-4 bg-body w-90 justify-content-center mx-auto">
        {{-- <h3>รายการเอกสาร</h3> --}}

        <!-- ย้าย form มาอยู่ตรงนี้ ครอบทั้งปุ่มลบและตาราง -->
        <form action="{{ route('user.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            @method('DELETE')

            <div class="row">
                <div class="col-8">
                    <h2>รายการเอกสาร</h2>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                            style="display:none;">ลบรายการทั้งหมด</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                            style="display:none;">ลบรายการที่เลือก</button>
                    </div>
                </div>
                <div class="col-2">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div>
                            <a href="{{ route('user.create') }}" class="btn btn-success mb-3">เพิ่มผู้ใช้</a>
                        </div>
                    </div>
                </div>
            </div>

            <table class="mint-table w-100">
                <thead class="text-white text-center bg-primary">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>ระดับผู้ใช้</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('user.edit', $user->id) }}'">
                            <td class="text-center" onclick="event.stopPropagation();">
                                <input type="checkbox" class="user-checkbox" name="selected_users[]"
                                    value="{{ $user->id }}">
                            </td>
                            <td class="text-center">{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->prefix->name ?? '' }} {{ $user->firstname }} {{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->user_type }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">ไม่พบข้อมูลผู้ใช้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>

        {{-- {{ $documents->links() }} --}}
    </div>

    <script>
        // ฟังก์ชันในการเลือกทั้งหมด
        document.getElementById('select-all').addEventListener('click', function(event) {
            let checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
            toggleDeleteButtons();
        });

        // การเพิ่มเหตุการณ์ให้กับ checkboxes แต่ละตัว
        let checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButtons);
        });

        // ฟังก์ชันในการแสดงปุ่มลบที่เหมาะสม
        function toggleDeleteButtons() {
            let selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
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

        // ฟอร์มค้นหาอัตโนมัติ
        const form = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const userTypeSelect = document.getElementById('user-type');

        function submitForm() {
            form.submit();
        }

        let typingTimer;
        const doneTypingInterval = 500; // milliseconds

        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(submitForm, doneTypingInterval);
        });

        searchInput.addEventListener('keydown', () => {
            clearTimeout(typingTimer);
        });

        userTypeSelect.addEventListener('change', submitForm);
    </script>
</x-layouts.app>
