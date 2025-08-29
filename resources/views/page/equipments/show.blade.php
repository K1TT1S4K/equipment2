<x-layouts.app>
    {{-- เอาไว้นับครุภัณฑ์ แต่ไม่ได้ใช้แล้ว --}}
    {{-- @php $count = 0; @endphp --}}

    <h3 class="text-dark mb-4">จัดการครุภัณฑ์</h3>

    <form action="{{ route('equipment.index') }}" method="GET" class="mb-3">
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
                placeholder="ค้นหาครุภัณฑ์" value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>

        </div>
    </form>

    {{-- <form action="#" method="GET">
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg p-2 mb-1 rounded" id="title_filter" name="title_filter"
                onchange="this.form.submit()">
                @foreach ($titles as $t)
                    <option value="{{ $t->id }}" {{ request('title_filter') == $t->id ? 'selected' : '' }}>
                        {{ $t->group }} - {{ $t->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="d-flex mb-2">
            <select class="form-control shadow-lg p-2 mb-1 me-2 rounded" id="unit_filter" name="unit_filter"
                onchange="this.form.submit()">
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
            <select class="form-control shadow-lg p-2 mb-1 me-2 rounded" id="location_filter" name="location_filter"
                onchange="this.form.submit()">
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
            <select class="form-control shadow-lg p-2 mb-1 rounded" id="user_filter" name="user_filter"
                onchange="this.form.submit()">
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
    </form> --}}
    {{-- ค้นหา --}}
    {{-- <form onsubmit="searchTable(); return false;">
        <div class="d-flex">
            <input type="text" class="form-control shadow-lg p-1 mb-3 rounded" id="equipments-search"
                placeholder="ค้นหาครุภัณฑ์">
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
        </div>
    </form> --}}
    {{-- แถบปุ่ม --}}
    <div class="row mt-1">
        <div class="d-flex justify-content-between">
            @can('admin-or-branch')
                {{-- @if (request()->query('bin_mode') == 1) --}}
                <button id="restoreFromTrashBtn"
                    class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded {{ request()->query('bin_mode') == 1 ? '' : 'd-none' }}">
                    <i class="fas fa-trash"></i> ย้ายออกจากถังขยะ
                </button>
                {{-- @else --}}
                <button id="moveToTrashBtn"
                    class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded  {{ request()->query('bin_mode') == 1 ? 'd-none' : '' }}">
                    <i class="fas fa-trash"></i> ย้ายไปที่ถังขยะ
                </button>
                {{-- @endif           --}}
                <button class="btn btn-danger ms-2 shadow-lg p-2 mb-3 rounded" id="goToBinBtn" onclick="goToBinMode()">
                    โหมดถังขยะ
                </button>
                <a href="{{ route('equipment.create') }}"
                    class="btn btn-success ms-2 shadow-lg p-2 mb-3 rounded ">เพิ่มข้อมูล</a>
            @endcan

            <a href="{{ route('equipment.export', $t->id) }}">
                <button class="btn btn-success  ms-2 shadow-lg p-2 mb-3 rounded">
                    Export Excel
                </button>
            </a>
        </div>
    </div>

    <div class="card shadow-lg p-3 mb-4 bg-body">
        <h3>รายการครุภัณฑ์</h3>
        {{-- ตาราง --}}

        <form action="{{ route('equipment.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            {{-- @method('DELETE') --}}
            <div class="row mb-3">
                <div class="col-4">
                    <div>
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                            style="display:none;">ย้ายรายการทั้งหมดไปที่ถังขยะ</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                            style="display:none;">ย้ายไปที่ถังขยะ</button>
                    </div>
                </div>
                <div class="col-4"></div>
                @can('admin-or-branch')
                    <div class="col-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div>
                                <!-- ปุ่มเพิ่มข้อมูล -->
                                <a href="{{ route('equipment.create') }}" class="btn btn-success mb-3">เพิ่มข้อมูล</a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-white text-center border border-dark">
                        <tr>
                            <th class="border align-middle" rowspan="2">
                                {{-- <div class="form-check d-flex justify-content-center align-items-center"
                                    style="height: 100%;"> --}}
                                    {{-- <input class="form-check-input" type="checkbox" id="select-all"
                                    style="transform: scale(1.5);"> --}}
                                    <input type="checkbox" id="select-all">
                                {{-- </div> --}}
                            </th>
                            <th class="border align-middle" rowspan="2">ลำดับ</th>
                            <th class="border align-middle" rowspan="2" style="width: 9%">รหัสครุภัณฑ์</th>
                            <th class="border align-middle" rowspan="2" style="width: 20%;">รายการ <br>( ยี่ห้อ,
                                ชนิด,
                                แบบ,
                                ขนาดและลักษณะ )</th>
                            <th class="border align-middle" rowspan="2">หน่วยนับ</th>
                            <th class="border align-middle" rowspan="2">จำนวน<br>คงเหลือ</th>
                            <th class="border align-middle" rowspan="2">ราคาต่อหน่วย <br>(บาท)</th>
                            <th class="border align-middle" rowspan="2">ราคารวม</th>
                            <th class="border align-middle" colspan="5" style="width:10%">สถานะ</th>
                            <th class="border align-middle" rowspan="2" style="width: 16%">หมายเหตุ</th>
                            <th class="border align-middle" rowspan="2">วันที่แก้ไข</th>
                            <th class="border align-middle" rowspan="2">วันที่สร้าง</th>
                            {{-- <th class="border align-middle" rowspan="2">จัดการ</th> --}}
                        </tr>
                        <tr class="text-center">
                            <th class="border align-middle" style="width: 2%">พบ</th>
                            <th class="border align-middle" style="width: 2%">ไม่พบ</th>
                            <th class="border align-middle" style="width: 2%">ชำรุด</th>
                            <th class="border align-middle" style="width: 2%">จำ<br>หน่าย</th>
                            <th class="border align-middle" style="width: 2%">โอน</th>
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

                            // $eqs = Equipment::withTrashed()->find($id);
                            // dd($equipment_trash);

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
                                    @php
                                        continue;
                                    @endphp
                                @endif

                                <tr class="text-center border border-dark">
                                    {{-- rgb(108, 117, 125) = secondary --}}
                                    {{-- rgb(167, 172, 177) = secondary - 40% --}}
                                    <td colspan="3" class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{ $equipment->equipmentType->name }}
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{ number_format($equipment->equipmentType->amount) }}
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{ number_format($equipment->equipmentType->price, 2) }}
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{ number_format($equipment->equipmentType->total_price, 2) }}
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{-- {{ $equipment->equipmentType->updated_at }} --}}
                                        {{ $equipment->equipmentType->updated_at->format('j') }}
                                        {{ $equipment->equipmentType->updated_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->equipmentType->updated_at->year + 543 }}
                                        {{ $equipment->equipmentType->updated_at->format('H:i:s') }}

                                    </td>
                                    <td class="bg-secondary text-white border align-middle">
                                        {{-- {{ $equipment->equipmentType->created_at }} --}}
                                        {{ $equipment->equipmentType->created_at->format('j') }}
                                        {{ $equipment->equipmentType->created_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->equipmentType->created_at->year + 543 }}
                                        {{ $equipment->equipmentType->created_at->format('H:i:s') }}

                                    </td>
                                </tr>

                                @forelse ($filterEquipments as $key => $equipment)
                                    @if ($equipment->equipment_type_id === $type)
                                        <tr class="text-center border border-dark">
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)">
                                                {{-- <div class="form-check d-flex justify-content-center align-items-center"
                                                style="height: 100%;">
                                                <input class="form-check-input checkbox-item"
                                                    value="{{ $equipment->id }}" type="checkbox"
                                                    style="transform: scale(1.5);">
                                            </div> --}}

                                                <input type="checkbox" class="equipment-checkbox"
                                                    name="selected_equipments[]" value="{{ $equipment->id }}">

                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{-- {{ ++$count }}<br> --}}
                                                {{ $loop->iteration + ($equipments->currentPage() - 1) * $equipments->perPage() }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->number }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->name }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->equipmentUnit->name }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->amount }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->price }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->total_price }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->status_found }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->status_not_found }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->status_broken }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->status_disposal }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{ $equipment->status_transfer }}
                                            </td>
                                            <td class="border border-dark text-start"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                <p><span class="text-muted">ผู้ดูแล:</span>
                                                    {{ $equipment->user?->prefix?->name }}{{ $equipment->user?->firstname }}
                                                </p>
                                                <hr>
                                                <p><span class="text-muted">ที่อยู่:
                                                    </span>{{ $equipment->location?->name }}</p>
                                                <hr>
                                                <p><span class="text-muted">คำอธิบาย:
                                                    </span>{{ $equipment->description }}
                                                </p>
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                                {{-- {{ $equipment->updated_at }} --}}
                                                {{ $equipment->updated_at->format('j') }}
                                                {{ $equipment->updated_at->locale('th')->translatedFormat('M') }}
                                                {{ $equipment->updated_at->year + 543 }}
                                                {{ $equipment->updated_at->format('H:i:s') }}
                                            </td>
                                            <td class="border border-dark align-middle"
                                                style="background-color: rgb(226, 227, 229)"
                                                onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
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
                                        <td colspan="100%" class="text-center">ไม่มีข้อมูล</td>
                                    </tr>
                                @endforelse

                                @php
                                    $displayedTypes[] = $type;
                                @endphp
                            @else
                                <tr class="text-center border border-dark">
                                    <td class="border border-dark align-middle">
                                        {{-- <div class="form-check d-flex justify-content-center align-items-center"
                                        style="height: 100%;">
                                        <input class="form-check-input checkbox-item" value="{{ $equipment->id }}"
                                            type="checkbox" style="transform: scale(1.5);">
                                    </div> --}}
                                        <input type="checkbox" class="equipment-checkbox"
                                            name="selected_equipments[]" value="{{ $equipment->id }}">

                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{-- {{ ++$count }}<br> --}}
                                        {{ $loop->iteration + ($equipments->currentPage() - 1) * $equipments->perPage() }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->number }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->name }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->equipmentUnit->name }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->amount) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->price, 2) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->total_price, 2) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->status_found) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->status_not_found) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->status_broken) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->status_disposal) }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ number_format($equipment->status_transfer) }}
                                    </td>
                                    <td class="border border-dark text-start"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        <p><span class="text-muted">ผู้ดูแล:</span>
                                            {{ $equipment->user?->prefix?->name }}{{ $equipment->user?->firstname }}
                                        </p>
                                        <hr>
                                        <p><span class="text-muted">ที่อยู่: </span>{{ $equipment->location?->name }}
                                        </p>
                                        <hr>
                                        <p><span class="text-muted">คำอธิบาย: </span>{{ $equipment->description }}
                                        </p>
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{-- {{ $equipment->updated_at }} --}}
                                        {{ $equipment->updated_at->format('j') }}
                                        {{ $equipment->updated_at->locale('th')->translatedFormat('M') }}
                                        {{ $equipment->updated_at->year + 543 }}
                                        {{ $equipment->updated_at->format('H:i:s') }}
                                    </td>
                                    <td class="border border-dark align-middle"
                                        onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
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
                                <td colspan="100%" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </form>

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
