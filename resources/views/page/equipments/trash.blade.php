<x-layouts.app>
    {{-- เอาไว้นับครุภัณฑ์ แต่ไม่ได้ใช้แล้ว --}}
    @php
        if (request('page')) {
            $count = (request('page') - 1) * 10;
        } else {
            $count = 0;
        }
    @endphp

    <h3 class="text-dark mb-4">กู้คืนครุภัณฑ์</h3>

    <form action="{{ route('equipment.trash') }}" method="GET" class="mb-3">
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg p-2 mb-1 rounded" id="title_filter" name="title_filter">
                @foreach ($titles as $t)
                    <option value="{{ $t->id }}" {{ request('title_filter') == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg p-2 mb-1 me-2 rounded" id="unit_filter" name="unit_filter">
                <option value="all"
                    {{ request('unit_filter') == 'all' || !request('unit_filter') ? 'selected' : '' }}>
                    ---หน่วยนับ---
                </option>
                @foreach ($equipment_units as $unit)
                    @continue(optional($fullEquipments->where('equipment_unit_id', $unit->id)->first())->title_id != request('title_filter') && $unit->is_locked == 1)
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
                    @continue(optional($fullEquipments->where('location_id', $location->id)->first())->title_id != request('title_filter') && $location->is_locked == 1)
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
                <option value="all"
                    {{ request('user_filter') == 'all' || !request('user_filter') ? 'selected' : '' }}>
                    ---ผู้ดูแลทั้งหมด---
                </option>

                @foreach ($users as $user)
                    @continue(optional($fullEquipments->where('user_id', $user->id)->first())->title_id != request('title_filter') && $user->is_locked == 1)
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
        <div class="d-flex">
            <input type="text" id="query" name="query" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหาจากข้อมูลครุภัณฑ์" value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            <button type="button" class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded"
                onclick="window.location='{{ route('equipment.trash') }}?title_filter={{Title::max('id')}}&unit_filter=all&location_filter=all&user_filter=all'"
                style="width: 10%">ล้างการค้นหา</button>
        </div>
    </form>

    <div class="card shadow-lg p-3 bg-body">
        <div class="row mb-2">
            <div class="col-4">
                <h3>รายการครุภัณฑ์</h3>
            </div>
            <div class="col-8 d-flex justify-content-end gap-2">
                <!-- ปุ่ม Bulk Actions (กู้คืน, ลบถาวร) ซ่อนไว้ก่อน -->
                <div id="bulk-restore-all" style="display: none;">
                    <form id="bulk-restore-form" action="{{ route('equipment.restoreMultiple') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_equipments" id="selected_equipments_json_restore">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            กู้คืนทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-restore-selected" style="display: none;">
                    <form id="bulk-restore-selected-form" action="{{ route('equipment.restoreMultiple') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_equipments" id="selected_equipments_json_restore_selected">
                        <button type="submit" class="btn btn-warning"
                            onclick="return confirm('คุณต้องการกู้คืนเอกสารที่เลือกใช่หรือไม่?')">
                            กู้คืนที่เลือก
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-all" style="display: none;">
                    <form id="bulk-force-delete-all-form" action="{{ route('equipment.forceDeleteMultiple') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_equipments" id="selected_equipments_json_force_delete_all">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('คุณต้องการลบถาวรเอกสารทั้งหมดที่เลือกใช่หรือไม่?')">
                            ลบถาวรทั้งหมด
                        </button>
                    </form>
                </div>

                <div id="bulk-delete-selected" style="display: none;">
                    <form id="bulk-force-delete-selected-form" action="{{ route('equipment.forceDeleteMultiple') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                        <input type="hidden" name="selected_equipments"
                            id="selected_equipments_json_force_delete_selected">
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
                    <th rowspan="2">
                        @can('admin-or-branch-or-officer')
                            <input type="checkbox" id="select-all">
                        @endcan
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
                {{-- @php
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

                        @if ($type == null) --}}
                {{-- <tr class="text-center" style="cursor: pointer;">
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
                        @else --}}
                {{-- <tr class="text-center" style="cursor: pointer;">
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
                                <td class="bg-secondary text-white"> --}}
                {{-- {{ $equipment->equipmentType->updated_at }} --}}
                {{-- {{ $equipment->equipmentType->updated_at->format('j') }}
                                    {{ $equipment->equipmentType->updated_at->locale('th')->translatedFormat('M') }}
                                    {{ $equipment->equipmentType->updated_at->year + 543 }}
                                    {{ $equipment->equipmentType->updated_at->format('H:i:s') }}

                                </td>
                                <td class="bg-secondary text-white"> --}}
                {{-- {{ $equipment->equipmentType->created_at }} --}}
                {{-- {{ $equipment->equipmentType->created_at->format('j') }}
                                    {{ $equipment->equipmentType->created_at->locale('th')->translatedFormat('M') }}
                                    {{ $equipment->equipmentType->created_at->year + 543 }}
                                    {{ $equipment->equipmentType->created_at->format('H:i:s') }}

                                </td>
                            </tr>
                        @endif --}}

                @forelse ($equipments as $key => $equipment)
                    @continue($equipment->title_id != request('title_filter') && $equipment->is_locked == 1)
                    <tr class="text-center" style="cursor: pointer;">
                        <td>
                            @can('admin-or-branch-or-officer')
                                <input type="checkbox" class="equipment-checkbox" name="selected_equipments[]"
                                    value="{{ $equipment->id }}">
                            @endcan
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
                            {{ $equipment->status_found }}
                        </td>
                        <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                            {{ $equipment->status_not_found }}
                        </td>
                        <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                            {{ $equipment->status_broken }}
                        </td>
                        <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                            {{ $equipment->status_disposal }}
                        </td>
                        <td onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                            {{ $equipment->status_transfer }}
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
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        {{-- ตัวแบ่งหน้า --}}
        <div class="d-flex justify-content-center">
            {{ $equipments->links() }}
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_equipments[]"]');
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
                // if (!form || !hiddenInput) return; // ❗ กัน error
                form.addEventListener('submit', function(e) {
                    const selectedIds = Array.from(document.querySelectorAll('.equipment-checkbox:checked'))
                        .map(cb => cb.value);
                    // console.log("Selected IDs:", selectedIds); // ✅ debug
                    if (selectedIds.length === 0) {
                        e.preventDefault();
                        alert('กรุณาเลือกเอกสารที่ต้องการ');
                        return false;
                    }
                    hiddenInput.value = selectedIds.join(',');
                });
            }

            bindFormSubmission('bulk-restore-form', 'selected_equipments_json_restore');
            bindFormSubmission('bulk-restore-selected-form', 'selected_equipments_json_restore_selected');
            bindFormSubmission('bulk-force-delete-all-form', 'selected_equipments_json_force_delete_all');
            bindFormSubmission('bulk-force-delete-selected-form', 'selected_equipments_json_force_delete_selected');
        });
    </script>
</x-layouts.app>
