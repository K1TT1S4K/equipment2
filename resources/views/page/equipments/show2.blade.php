<x-layouts.app>
    @php $count = 0; @endphp
    <h1 class="mb-3">ครุภัณฑ์</h1>

    {{-- ฟอร์มกรองข้อมูล --}}
    <form action="#" method="GET" id="filterForm">
        <div class="d-flex flex-wrap gap-3 mb-3 align-items-end">
            <div>
                <label for="title_filter" class="form-label">กลุ่มครุภัณฑ์</label>
                <select class="form-select" id="title_filter" name="title_filter"
                    onchange="document.getElementById('filterForm').submit()">
                    @foreach ($titles as $t)
                        <option value="{{ $t->id }}" {{ request('title_filter') == $t->id ? 'selected' : '' }}>
                            {{ $t->group }} - {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="unit_filter" class="form-label">หน่วยนับ</label>
                <select class="form-select" id="unit_filter" name="unit_filter"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="all"
                        {{ request('unit_filter') == 'all' || !request('unit_filter') ? 'selected' : '' }}>
                        ---หน่วยนับ---
                    </option>
                    @foreach ($equipments->where('title_id', request('title_filter'))->unique('equipment_unit_id') as $unit)
                        <option value="{{ $unit->equipment_unit_id }}"
                            {{ request('unit_filter') == $unit->equipment_unit_id ? 'selected' : '' }}>
                            {{ $unit->equipmentUnit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="location_filter" class="form-label">สถานที่</label>
                <select class="form-select" id="location_filter" name="location_filter"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="all"
                        {{ request('location_filter') == 'all' || !request('location_filter') ? 'selected' : '' }}>
                        ---สถานที่ทั้งหมด---
                    </option>
                    @foreach ($equipments->where('title_id', request('title_filter'))->unique('location_id') as $location)
                        <option value="{{ $location->location_id }}"
                            {{ request('location_filter') == $location->location_id ? 'selected' : '' }}>
                            @if ($location->location_id == null)
                                ---ไม่ได้กำหนดสถานที่---
                            @else
                                {{ $location->location->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="user_filter" class="form-label">ผู้ดูแล</label>
                <select class="form-select" id="user_filter" name="user_filter"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="all"
                        {{ request('user_filter') == 'all' || !request('user_filter') ? 'selected' : '' }}>
                        ---ผู้ดูแลทั้งหมด---
                    </option>
                    @foreach ($equipments->where('title_id', request('title_filter'))->unique('user_id') as $user)
                        <option value="{{ $user->user_id }}"
                            {{ request('user_filter') == $user->user_id ? 'selected' : '' }}>
                            @if ($user->user_id == null)
                                ---ไม่ได้กำหนดผู้ดูแล---
                            @else
                                {{ $user->user->prefix->name }}{{ $user->user->firstname }}
                                {{ $user->user->lastname }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ช่องค้นหา --}}
            <div class="flex-grow-1">
                <label for="equipments-search" class="form-label">ค้นหา</label>
                <input type="text" id="equipments-search" class="form-control" placeholder="ค้นหา..."
                    onkeydown="if(event.key==='Enter'){searchTable(); return false;}">
            </div>
            <div>
                <button type="button" class="btn btn-primary mt-4" onclick="searchTable()">ค้นหา</button>
            </div>
        </div>
    </form>

    {{-- ปุ่มจัดการ --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        @can('admin-or-branch')
            <button id="moveToTrashBtn"
                class="btn btn-danger btn-sm me-2 {{ request()->query('bin_mode') == 1 ? 'd-none' : '' }}">
                <i class="fas fa-trash"></i> ย้ายไปถังขยะ
            </button>
            <button id="restoreFromTrashBtn"
                class="btn btn-primary btn-sm me-2 {{ request()->query('bin_mode') == 1 ? '' : 'd-none' }}">
                <i class="fas fa-trash-restore"></i> ย้ายออกจากถังขยะ
            </button>
            <button id="goToBinBtn" class="btn btn-secondary btn-sm">
                โหมดถังขยะ
            </button>
            <a href="{{ route('equipment.create') }}" class="btn btn-success btn-sm ms-2">
                <i class="fas fa-plus"></i> เพิ่มข้อมูล
            </a>
        @endcan
    </div>
    <div>
        <a href="/export" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

{{-- ห่อด้วย card --}}
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">รายการครุภัณฑ์</h5>
    </div>
    <div class="card-body p-3 table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-light text-center align-middle">
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="select-all" style="transform: scale(1.3);">
                    </th>
                    <th>ลำดับ</th>
                    <th style="width: 120px;">รหัสครุภัณฑ์</th>
                    <th>รายการ (ยี่ห้อ, ชนิด, แบบ, ขนาดและลักษณะ)</th>
                    <th>หน่วยนับ</th>
                    <th>จำนวนคงเหลือ</th>
                    <th>ราคาต่อหน่วย (บาท)</th>
                    <th>ราคารวม</th>
                    <th colspan="5">สถานะ</th>
                    <th style="width: 180px;">หมายเหตุ</th>
                    <th>วันที่แก้</th>
                    <th>วันที่สร้าง</th>
                    <th style="width: 80px;">จัดการ</th>
                </tr>
                <tr class="table-secondary text-center">
                    <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    <th>พบ</th>
                    <th>ไม่พบ</th>
                    <th>ชำรุด</th>
                    <th>จำหน่าย</th>
                    <th>โอน</th>
                    <th></th><th></th><th></th><th></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $titleId = request()->query('title_filter');
                    $unitId = request()->query('unit_filter');
                    $locationId = request()->query('location_filter');
                    $userId = request()->query('user_filter');
                    $sFound = request()->query('status_found');
                    $sNotFound = request()->query('status_not_found');
                    $sBroken = request()->query('status_broken');
                    $sDisposal = request()->query('status_disposal');
                    $sTransfer = request()->query('status_transfer');

                    function filterBySelected(
                        $equipments,
                        $titleId,
                        $unitId,
                        $locationId,
                        $userId,
                        $sFound,
                        $sNotFound,
                        $sBroken,
                        $sDisposal,
                        $sTransfer,
                    ) {
                        $filtered = $equipments->where('title_id', $titleId);

                        if ($unitId !== 'all') {
                            $filtered = $filtered->where('equipment_unit_id', $unitId);
                        }

                        if ($locationId !== 'all') {
                            $filtered = $filtered->where('location_id', $locationId);
                        }

                        if ($userId !== 'all') {
                            $filtered = $filtered->where('user_id', $userId);
                        }

                        if ($sFound !== 'all' && $sFound !== null) {
                            $filtered = $filtered->where('status_found', '>=', $sFound);
                        }

                        if ($sNotFound !== 'all' && $sNotFound !== null) {
                            $filtered = $filtered->where('status_not_found', '>=', $sNotFound);
                        }

                        if ($sBroken !== 'all' && $sBroken !== null) {
                            $filtered = $filtered->where('status_broken', '>=', $sBroken);
                        }

                        if ($sDisposal !== 'all' && $sDisposal !== null) {
                            $filtered = $filtered->where('status_disposal', '>=', $sDisposal);
                        }

                        if ($sTransfer !== 'all' && $sTransfer !== null) {
                            $filtered = $filtered->where('status_transfer', '>=', $sTransfer);
                        }

                        return $filtered;
                    }

                    $eqs = $equipments;
                    $binMode = request()->query('bin_mode');
                    if ($binMode == 1) {
                        $eqs = $equipment_trash;
                    }
                    $filterEquipments = filterBySelected(
                        $eqs,
                        $titleId,
                        $unitId,
                        $locationId,
                        $userId,
                        $sFound,
                        $sNotFound,
                        $sBroken,
                        $sDisposal,
                        $sTransfer,
                    );

                    $displayedTypes = [];
                @endphp

                @forelse ($filterEquipments as $key => $equipment)
                    @php
                        $type = $equipment->equipment_type_id;
                    @endphp
                    @if ($type !== null)
                        @if (in_array($type, $displayedTypes))
                            @continue
                        @endif

                        {{-- แถวหัวข้อประเภท --}}
                        <tr class="table-primary text-center align-middle">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-start fw-bold">{{ $equipment->equipmentType->name }}</td>
                            <td></td>
                            <td>{{ $equipment->equipmentType->amount }}</td>
                            <td>{{ number_format($equipment->equipmentType->price, 2) }}</td>
                            <td>{{ number_format($equipment->equipmentType->total_price, 2) }}</td>
                            <td colspan="6"></td>
                            <td>{{ $equipment->equipmentType->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $equipment->equipmentType->created_at->format('d/m/Y H:i') }}</td>
                            <td></td>
                        </tr>

                        {{-- รายการย่อยประเภทนี้ --}}
                        @foreach ($filterEquipments->where('equipment_type_id', $type) as $eq)
                            <tr class="text-center align-middle">
                                <td>
                                    <input class="form-check-input checkbox-item" type="checkbox"
                                        value="{{ $eq->id }}" style="transform: scale(1.3);">
                                </td>
                                <td>{{ ++$count }}</td>
                                <td>{{ $eq->number }}</td>
                                <td class="text-start">{{ $eq->name }}</td>
                                <td>{{ $eq->equipmentUnit->name }}</td>
                                <td>{{ $eq->amount }}</td>
                                <td>{{ number_format($eq->price, 2) }}</td>
                                <td>{{ number_format($eq->total_price, 2) }}</td>
                                <td>{{ $eq->status_found }}</td>
                                <td>{{ $eq->status_not_found }}</td>
                                <td>{{ $eq->status_broken }}</td>
                                <td>{{ $eq->status_disposal }}</td>
                                <td>{{ $eq->status_transfer }}</td>
                                <td class="text-start">
                                    <p><small class="text-muted">ผู้ดูแล:</small>
                                        {{ $eq->user?->prefix?->name }}{{ $eq->user?->firstname }}
                                        {{ $eq->user?->lastname }}</p>
                                    <hr class="my-1">
                                    <p><small class="text-muted">ที่อยู่:</small>
                                        {{ $eq->location?->name ?? 'ไม่ได้กำหนดสถานที่' }}</p>
                                    <hr class="my-1">
                                    <p><small class="text-muted">คำอธิบาย:</small> {{ $eq->description }}</p>
                                </td>
                                <td>{{ $eq->updated_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $eq->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('equipment.edit', $eq->id) }}" class="btn btn-sm btn-warning"
                                        title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @php
                            $displayedTypes[] = $type;
                        @endphp
                    @else
                        {{-- กรณี equipment ไม่มี equipment_type_id --}}
                        <tr class="text-center align-middle">
                            <td>
                                <input class="form-check-input checkbox-item" type="checkbox"
                                    value="{{ $equipment->id }}" style="transform: scale(1.3);">
                            </td>
                            <td>{{ ++$count }}</td>
                            <td>{{ $equipment->number }}</td>
                            <td class="text-start">{{ $equipment->name }}</td>
                            <td>{{ $equipment->equipmentUnit->name }}</td>
                            <td>{{ $equipment->amount }}</td>
                            <td>{{ number_format($equipment->price, 2) }}</td>
                            <td>{{ number_format($equipment->total_price, 2) }}</td>
                            <td>{{ $equipment->status_found }}</td>
                            <td>{{ $equipment->status_not_found }}</td>
                            <td>{{ $equipment->status_broken }}</td>
                            <td>{{ $equipment->status_disposal }}</td>
                            <td>{{ $equipment->status_transfer }}</td>
                            <td class="text-start">
                                <p><small class="text-muted">ผู้ดูแล:</small>
                                    {{ $equipment->user?->prefix?->name }}{{ $equipment->user?->firstname }}
                                    {{ $equipment->user?->lastname }}</p>
                                <hr class="my-1">
                                <p><small class="text-muted">ที่อยู่:</small>
                                    {{ $equipment->location?->name ?? 'ไม่ได้กำหนดสถานที่' }}</p>
                                <hr class="my-1">
                                <p><small class="text-muted">คำอธิบาย:</small> {{ $equipment->description }}</p>
                            </td>
                            <td>{{ $equipment->updated_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $equipment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('equipment.edit', $equipment->id) }}"
                                    class="btn btn-sm btn-warning" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="17" class="text-center">ไม่มีข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>




    {{-- JavaScript --}}
    <script>
        // ฟังก์ชันค้นหาในตาราง (ตัวอย่างง่าย)
        function searchTable() {
            const input = document.getElementById('equipments-search');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('table tbody');
            const rows = table.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const textContent = row.textContent.toLowerCase();
                if (textContent.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // เลือก/ไม่เลือก checkbox ทั้งหมด
        document.getElementById('select-all').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.checkbox-item').forEach(cb => cb.checked = checked);
        });

        // ปุ่มโหมดถังขยะ (ตัวอย่าง)
        document.getElementById('goToBinBtn').addEventListener('click', function() {
            const url = new URL(window.location.href);
            if (url.searchParams.get('bin_mode') == 1) {
                url.searchParams.delete('bin_mode');
            } else {
                url.searchParams.set('bin_mode', 1);
            }
            window.location.href = url.toString();
        });
    </script>
</x-layouts.app>
