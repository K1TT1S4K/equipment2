<x-layouts.app>
    {{-- เอาไว้นับครุภัณฑ์ แต่ไม่ได้ใช้แล้ว --}}
    @php
        if (request('page')) {
            $count = (request('page') - 1) * 10;
        } else {
            $count = 0;
        }
    @endphp
    {{-- {{ dd(
        $equipments[0]->amount,
        $equipments[0]->amount -
            $equipments[0]->getStatusBroken->sum('amount') -
            $equipments[0]->getStatusNotFound->sum('amount') -
            $equipments[0]->getStatusDisposal->sum('amount') -
            $equipments[0]->getStatusTransfer->sum('amount'),
        $equipments[0]->getStatusNotFound->sum('amount'),
        $equipments[0]->getStatusBroken->sum('amount'),
        $equipments[0]->getStatusDisposal->sum('amount'),
        $equipments[0]->getStatusTransfer->sum('amount'),
    ) }} --}}
    <h3 class="text-dark mb-4">จัดการครุภัณฑ์</h3>
    <form action="{{ route('equipment.index') }}" method="GET" class="mb-3">
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg rounded" id="title_filter" name="title_filter">
                @foreach ($titles as $t)
                    <option value="{{ $t->id }}" {{ request('title_filter') == $t->id ? 'selected' : '' }}>
                        {{ $t->group }} - {{ $t->name }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-secondary ms-2 shadow-lg p-2 rounded" data-bs-toggle="modal"
                data-bs-target="#titleModal">
                โคลน </button>
            {{-- <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button> --}}
        </div>
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg p-2 mb-1 me-2 rounded" id="unit_filter" name="unit_filter">
                <option value="all"
                    {{ request('unit_filter') == 'all' || !request('unit_filter') ? 'selected' : '' }}>
                    ---หน่วยนับ---
                </option>
                @foreach ($equipment_units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_filter') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
            <select class="form-control shadow-lg p-2 mb-1 me-2 rounded" id="location_filter" name="location_filter">
                <option value="all"
                    {{ request('location_filter') == 'all' || !request('location_filter') ? 'selected' : '' }}>
                    ---สถานที่ทั้งหมด---
                </option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}"
                        {{ request('location_filter') == $location->id ? 'selected' : '' }}>
                        @if ($location->id == null)
                            ---ไม่ได้กำหนดสถานที่---
                        @else
                            {{ $location->name }}
                        @endif
                    </option>
                @endforeach
            </select>
            <select class="form-control shadow-lg p-2 mb-1 rounded" id="user_filter" name="user_filter">
                <option value="all" {{ request('user_filter') == 'all' ? 'selected' : '' }}>
                    ---ผู้ดูแลทั้งหมด---
                </option>
                <option value="" {{ request('user_filter') == null ? 'selected' : '' }}>
                    สาขาเทคโนโลยีคอมพิวเตอร์
                </option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>
                        @if ($user->id == null)
                            ---ไม่ได้กำหนดผู้ดูแล---
                        @else
                            {{ $user->prefix->name }}{{ $user->firstname }}
                            {{ $user->lastname }}
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
        {{-- {{dd(request('user_filter'))}} --}}

        <div class="d-flex">
            <input type="text" id="query" name="query" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหาจากข้อมูลครุภัณฑ์" value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            <button type="button" class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded"
                onclick="window.location='{{ route('equipment.index') }}?title_filter=1&unit_filter=all&location_filter=all&user_filter=all'"
                style="width: 10%">ล้างการค้นหา</button>
        </div>
    </form>

    <div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="titleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="titleModalLabel">จัดการข้อมูลหัวข้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>กลุ่ม</th>
                                <th>ชื่อหัวข้อ</th>
                                <th>การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody id="cloneTitleTableBody">
                            {{-- โหลดข้อมูลด้วย JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg p-3 mb-4 bg-body">
        <h3>รายการครุภัณฑ์</h3>
        {{-- ตาราง --}}

        <form action="{{ route('equipment.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            {{-- @method('DELETE') --}}
            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
            {{-- {{dd(url()->full())}} --}}
            <div class="d-flex mb-3">
                <div class="me-auto p-3">
                    <div>
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger" id="delete-all-btn"
                            style="display:none;">ย้ายรายการทั้งหมดไปที่ถังขยะ</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger" id="delete-selected-btn"
                            style="display:none;">ย้ายไปที่ถังขยะ</button>
                    </div>
                </div>

                <div class="p-2">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div>
                            <a href="{{ route('equipment.export', request()->query()) }}" class="btn btn-primary mb-3">
                                ส่งออกข้อมูล
                            </a>
                        </div>
                    </div>
                </div>

                @can('admin-or-branch')
                    <div class="p-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div>
                                <!-- ปุ่มเพิ่มข้อมูล -->
                                <a href="{{ route('equipment.create') }}" class="btn btn-success mb-3">เพิ่มข้อมูล</a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            {{-- <div class="table-responsive"> --}}
            <table class="table table-hover w-full">
                <thead class="text-center table-dark align-middle">
                    <tr class="text-center">
                        <th rowspan="2">
                            {{-- <div class="form-check d-flex justify-content-center align-items-center"
                                    style="height: 100%;"> --}}
                            {{-- <input class="form-check-input" type="checkbox" id="select-all"
                                    style="transform: scale(1.5);"> --}}
                            @if ('admin-or-branch-or-officer')
                                <input type="checkbox" id="select-all">
                            @endif
                            {{-- </div> --}}
                        </th>
                        <th class="align-middle" rowspan="2">ลำดับ</th>
                        <th class="align-middle" rowspan="2" style="width: 9%">รหัสครุภัณฑ์</th>
                        <th class="align-middle" rowspan="2" style="width: 20%;">รายการ <br>( ยี่ห้อ,
                            ชนิด,
                            แบบ,
                            ขนาดและลักษณะ )</th>
                        <th class="align-middle" rowspan="2">หน่วยนับ</th>
                        <th class="align-middle" rowspan="2">จำนวน<br>คงเหลือ</th>
                        <th class="align-middle" rowspan="2">ราคาต่อหน่วย <br>(บาท)</th>
                        <th class="align-middle" rowspan="2">ราคารวม</th>
                        <th class="align-middle" colspan="5" style="width:10%">สถานะ</th>
                        <th class="align-middle" rowspan="2" style="width: 16%">หมายเหตุ</th>
                        <th class="align-middle" rowspan="2">วันที่แก้ไข</th>
                        <th class="align-middle" rowspan="2">วันที่สร้าง</th>
                        {{-- <th class="align-middle" rowspan="2">จัดการ</th> --}}
                    </tr>
                    <tr class="text-center">
                        <th class="align-middle" style="width: 2%">พบ</th>
                        <th class="align-middle" style="width: 2%">ไม่พบ</th>
                        <th class="align-middle" style="width: 2%">ชำรุด</th>
                        <th class="align-middle" style="width: 2%">จำ<br>หน่าย</th>
                        <th class="align-middle" style="width: 2%">โอน</th>
                    </tr>
                </thead>
                <tbody class="align-middle p-3">
                    @php
                        $displayedTypes = [];
                    @endphp
                    @forelse ($equipments as $key => $equipment)
                        @php
                            $type = $equipment->equipment_type_id;
                        @endphp
                        @if (in_array($type, $displayedTypes))
                            @php
                                continue;
                            @endphp
                        @endif

                        @if ($type == null)
                            <tr class="text-center" style="cursor: pointer;">
                                <td colspan="3" class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                    ไม่มีประเภท
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                            </tr>
                        @else
                            <tr class="text-center" style="cursor: pointer;">
                                <td colspan="3" class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                    {{ $equipment->equipmentType->name }}
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                    {{ number_format($equipment->equipmentType->amount) }}
                                </td>
                                <td class="bg-secondary text-white">
                                    {{ number_format($equipment->equipmentType->price, 2) }}
                                </td>
                                <td class="bg-secondary text-white">
                                    {{ number_format($equipment->equipmentType->total_price, 2) }}
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                </td>
                                <td class="bg-secondary text-white">
                                    {{-- {{ $equipment->equipmentType->updated_at }} --}}
                                    {{ $equipment->equipmentType->updated_at->format('j') }}
                                    {{ $equipment->equipmentType->updated_at->locale('th')->translatedFormat('M') }}
                                    {{ $equipment->equipmentType->updated_at->year + 543 }}
                                    {{ $equipment->equipmentType->updated_at->format('H:i:s') }}

                                </td>
                                <td class="bg-secondary text-white">
                                    {{-- {{ $equipment->equipmentType->created_at }} --}}
                                    {{ $equipment->equipmentType->created_at->format('j') }}
                                    {{ $equipment->equipmentType->created_at->locale('th')->translatedFormat('M') }}
                                    {{ $equipment->equipmentType->created_at->year + 543 }}
                                    {{ $equipment->equipmentType->created_at->format('H:i:s') }}

                                </td>
                            </tr>
                        @endif

                        @forelse ($equipments as $key => $equipment)
                            @if ($equipment->equipment_type_id === $type)
                                <tr class="text-center" style="cursor: pointer;">
                                    <td>
                                        @if ('admin-or-branch-or-officer')
                                            <input type="checkbox" class="equipment-checkbox"
                                                name="selected_equipments[]" value="{{ $equipment->id }}">
                                        @endif
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ ++$count }}<br>
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->number }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->name }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->equipmentUnit->name }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->amount }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->price }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->total_price }}
                                        {{-- {{dd($equipment->original_id, $fullEquipments)}} --}}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->original_id
                                            ? $fullEquipments->firstWhere('id', $equipment->original_id)->amount -
                                                $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusBroken->sum('amount') -
                                                $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusNotFound->sum('amount') -
                                                $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusDisposal->sum('amount') -
                                                $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusTransfer->sum('amount')
                                            : $equipment->amount -
                                                $equipment->getStatusBroken->sum('amount') -
                                                $equipment->getStatusNotFound->sum('amount') -
                                                $equipment->getStatusDisposal->sum('amount') -
                                                $equipment->getStatusTransfer->sum('amount') }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->original_id ? $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusNotFound->sum('amount') : $equipment->getStatusNotFound->sum('amount') }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->original_id ? $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusBroken->sum('amount') : $equipment->getStatusBroken->sum('amount') }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->original_id ? $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusDisposal->sum('amount') : $equipment->getStatusDisposal->sum('amount') }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->original_id ? $fullEquipments->firstWhere('id', $equipment->original_id)->getStatusTransfer->sum('amount') : $equipment->getStatusTransfer->sum('amount') }}
                                    </td>
                                    <td class="text-start"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        <p><span class="text-muted">ผู้ดูแล:</span>
                                            {{ $equipment->user ? ($equipment->user?->prefix?->name && $equipment->user?->firstname ? $equipment->user?->prefix?->name . ' ' . $equipment->user?->firstname : '-') : 'สาขาเทคโนโลยีคอมพิวเตอร์' }}
                                        </p>
                                        <hr>
                                        <p><span class="text-muted">ที่อยู่:
                                            </span>{{ $equipment->location?->name ?? '-' }}</p>
                                        <hr>
                                        <p class="mb-0"><span class="text-muted">คำอธิบาย:
                                            </span>{{ $equipment->description ?? '-' }}
                                        </p>
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{-- {{ $equipment->updated_at }} --}}
                                        {{ $equipment->updated_at->format('j') }}
                                        {{ $equipment->updated_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->updated_at->year + 543 }}
                                        {{ $equipment->updated_at->format('H:i:s') }}
                                    </td>
                                    <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{-- {{ $equipment->created_at }} --}}
                                        {{ $equipment->created_at->format('j') }}
                                        {{ $equipment->created_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->created_at->year + 543 }}
                                        {{ $equipment->created_at->format('H:i:s') }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse

                        @php
                            $displayedTypes[] = $type;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>

        {{-- ตัวแบ่งหน้า --}}
        <div class="d-flex justify-content-center">
            {{ $equipments->links() }}
        </div>
    </div>
    </div>
    <script>
        // เลือก container หรือ document
        const equipment = document;
        console.log("Hello world!");
        equipment.getElementById('select-all').addEventListener('click', function(event) {
            let checkboxes = equipment.querySelectorAll('.equipment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
            toggleDeleteButtons();
        });

        let checkboxes = equipment.querySelectorAll('.equipment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButtons);
        });

        function toggleDeleteButtons() {
            let selectedCheckboxes = equipment.querySelectorAll('.equipment-checkbox:checked');
            let deleteSelectedBtn = equipment.getElementById('delete-selected-btn');
            let deleteAllBtn = equipment.getElementById('delete-all-btn');

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
