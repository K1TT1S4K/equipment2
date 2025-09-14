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
                        {{ $t->group }} - {{ $t->name }}
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
                <option value="all"
                    {{ request('user_filter') == 'all' || !request('user_filter') ? 'selected' : '' }}>
                    ---ผู้ดูแลทั้งหมด---
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
        <div class="d-flex">
            <input type="text" id="query" name="query" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหาจากข้อมูลครุภัณฑ์" value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            <button type="button" class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded"
                onclick="window.location='{{ route('equipment.trash') }}'" style="width: 10%">ล้างการค้นหา</button>   
        </div>
    </form>

    <div class="card shadow-lg p-3 bg-body">
<div class="row mb-2">        <div class="col-4">
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
                <form id="bulk-restore-selected-form" action="{{ route('equipment.restoreMultiple') }}" method="POST">
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
                <form id="bulk-force-delete-all-form" action="{{ route('equipment.forceDeleteMultiple') }}" method="POST">
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
                <form id="bulk-force-delete-selected-form" action="{{ route('equipment.forceDeleteMultiple') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                    <input type="hidden" name="selected_equipments" id="selected_equipments_json_force_delete_selected">
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบถาวรเอกสารที่เลือกใช่หรือไม่?')">
                        ลบถาวรที่เลือก
                    </button>
                </form>
            </div>
        </div></div>

        {{-- <div class="table-responsive"> --}}
        <table class="table table-hover w-full">
            <thead class="text-center table-dark align-middle">
                <tr class="text-center">
                    <th rowspan="2">
                        {{-- <div class="form-check d-flex justify-content-center align-items-center"
                                    style="height: 100%;"> --}}
                        {{-- <input class="form-check-input" type="checkbox" id="select-all"
                                    style="transform: scale(1.5);"> --}}
                        <input type="checkbox" id="select-all">
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
                    <th class="align-middle" rowspan="2">วันที่ลบ</th>
                    {{-- <th class="align-middle" rowspan="2">วันที่สร้าง</th> --}}
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
                    @if ($type !== null)
                        @if (in_array($type, $displayedTypes))
                            @php
                                continue;
                            @endphp
                        @endif

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
                            </td>
                        </tr>

                        @forelse ($equipments as $key => $equipment)
                            @if ($equipment->equipment_type_id === $type)
                                <tr class="text-center" style="cursor: pointer;">
                                    <td class="soft-grey">
                                        <input type="checkbox" class="equipment-checkbox"
                                            name="selected_equipments[]" value="{{ $equipment->id }}">
                                    </td>
                                    <td class="soft-grey">
                                        {{ ++$count }}<br>
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->number }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->name }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->equipmentUnit->name }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->amount }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->price }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->total_price }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->status_found }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->status_not_found }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->status_broken }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->status_disposal }}
                                    </td>
                                    <td class="soft-grey">
                                        {{ $equipment->status_transfer }}
                                    </td>
                                    <td class="text-start soft-grey">
                                        <p><span class="text-muted">ผู้ดูแล:</span>
                                            {{-- {{ $equipment->user?->prefix?->name }} {{ $equipment->user?->firstname }} --}}
                                            {{ $equipment->user?->prefix?->name && $equipment->user?->firstname ? $equipment->user?->prefix?->name . ' ' . $equipment->user?->firstname : '-' }}
                                        </p>
                                        <hr>
                                        <p><span class="text-muted">ที่อยู่:
                                            </span>{{ $equipment->location?->name ?? '-' }}</p>
                                        <hr>
                                        <p class="mb-0"><span class="text-muted">คำอธิบาย:
                                            </span>{{ $equipment->description ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="soft-grey">
                                        {{-- {{ $equipment->updated_at }} --}}
                                        {{ $equipment->deleted_at->format('j') }}
                                        {{ $equipment->deleted_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->deleted_at->year + 543 }}
                                        {{ $equipment->deleted_at->format('H:i:s') }}
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
                    @else
                        <tr class="text-center">
                            <td>
                                <input type="checkbox" class="equipment-checkbox" name="selected_equipments[]"
                                    value="{{ $equipment->id }}">

                            </td>
                            <td>
                                {{ ++$count }}<br>
                                {{-- {{ $loop->iteration + ($equipments->currentPage() - 1) * $equipments->perPage() }} --}}
                            </td>
                            <td>
                                {{ $equipment->number }}
                            </td>
                            <td>
                                {{ $equipment->name }}
                            </td>
                            <td>
                                {{ $equipment->equipmentUnit->name }}
                            </td>
                            <td>
                                {{ number_format($equipment->amount) }}
                            </td>
                            <td>
                                {{ number_format($equipment->price, 2) }}
                            </td>
                            <td>
                                {{ number_format($equipment->total_price, 2) }}
                            </td>
                            <td>
                                {{ number_format($equipment->status_found) }}
                            </td>
                            <td>
                                {{ number_format($equipment->status_not_found) }}
                            </td>
                            <td>
                                {{ number_format($equipment->status_broken) }}
                            </td>
                            <td>
                                {{ number_format($equipment->status_disposal) }}
                            </td>
                            <td>
                                {{ number_format($equipment->status_transfer) }}
                            </td>
                            <td class="text-start">
                                <p><span class="text-muted">ผู้ดูแล:</span>
                                    {{ $equipment->user?->id }}
                                    {{-- {{ $equipment->user?->prefix?->name }} {{ $equipment->user?->firstname }} --}}
                                    {{ $equipment->user?->prefix?->name && $equipment->user?->firstname ? $equipment->user?->prefix?->name . ' ' . $equipment->user?->firstname : '-' }}
                                </p>
                                <hr>
                                <p><span class="text-muted">ที่อยู่:
                                    </span>{{ $equipment->location?->name ?? '-' }}
                                </p>
                                <hr>
                                <p class="mb-0"><span class="text-muted">คำอธิบาย:
                                    </span>{{ $equipment->description ?? '-' }}
                                </p>
                            </td>
                            <td>
                                {{ $equipment->deleted_at->format('j') }}
                                {{ $equipment->deleted_at->locale('th')->translatedFormat('M') }}
                                {{ $equipment->deleted_at->year + 543 }}
                                {{ $equipment->deleted_at->format('H:i:s') }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ตัวแบ่งหน้า --}}
        <div class="d-flex justify-content-center">
            {{-- ไว้ดูค่าเพื่อ debug --}}
            {{-- <pre>
                    {{ print_r(request()->all(), true) }}
    {{ $equipments->url(2) }}
</pre> --}}
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
