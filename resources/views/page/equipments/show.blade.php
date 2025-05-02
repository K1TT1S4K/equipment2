<x-layouts.app>
    @php $count = 0; @endphp

    <!-- ปุ่ม Export Excel -->
    <div class="d-flex justify-content-end mb-3">
        {{-- <a href="#" class="btn btn-success px-3">
            Export Excel
        </a> --}}
        {{-- <a href="{{ route('equipment.trash')}}" class="btn btn-danger">ถังขยะ</a> --}}
        <a href="{{ route('equipment.add')}}" class="btn btn-success px-3">เพิ่มครุภัณฑ์</a>
    </div>
    <table class="table table-striped table-hover shadow-lg p-3 mb-5 bg-body rounded">
        <thead class="table-dark">
            <tr class="text-center">
                <th class="border align-middle">#</th>
                <th class="border align-middle">รหัสครุภัณฑ์</th>
                <th class="border" style="width: 20%">รายการ <br>( ยี่ห้อ, ชนิด, แบบ, ขนาดและลักษณะ )</th>
                <th class="border align-middle">หน่วยนับ</th>
                <th class="border align-middle">จำนวน<br>คงเหลือ</th>
                <th class="border">ราคาต่อหน่วย <br>(บาท)</th>
                <th class="border align-middle">ราคารวม</th>
                <th class="border align-middle">ประเภท</th>
                <th class="border align-middle">สถานะ</th>
                <th class="border align-middle">หัวข้อ</th>
                <th class="border align-middle">หมายเหตุ</th>
                <th class="border align-middle">สถานที่</th>
                <th class="border align-middle">ผู้ดูแลครุภัณฑ์</th>
                <th class="border align-middle">จัดการ</th>
            </tr>

        </thead>
        <tbody>
            @forelse ($equipments as $key => $equipment)
                <tr class="text-center border border-dark">
                    <td class="border border-dark align-middle">{{ $equipment->id}} <br> ({{ $equipment->amount }})</td>
                    <td class="border border-dark align-middle">{{ $equipment->number}}</td>
                    <td class="border border-dark align-middle">{{ $equipment->name }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->equipmentUnit->name }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->amount }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->price }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->total_price }}</td>
                    <td class="border border-dark align-middle">{{ optional($equipment->equipmentType)->name }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->status }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->title->name }}</td>
                    <td class="border border-dark align-middle">{{ $equipment->description }}</td>
                    <td class="border border-dark align-middle">{{ optional($equipment->location)->name }}</td>
                    <td class="border border-dark align-middle">{{ optional($equipment->user)->name }}</td>
                    <td class="border border-dark align-middle">
                        <!-- ปุ่มเปิดฟอร์มแก้ไข -->
                        {{-- <a href="{{route('equipment.edit')}}" class="btn btn-warning"></a> --}}
                        <button class="btn btn-warning">แก้ไข</button>

                        <!-- ปุ่มลบ (จะใช้ฟอร์ม POST เพื่อป้องกันการใช้ GET method) -->
                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE') <!-- ใช้ HTTP DELETE สำหรับการลบ -->
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('คุณต้องการย้ายข้อมูลนี้ไปยังถังขยะหรือไม่?')">ลบ</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">ไม่มีข้อมูล</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{$equipments->links()}}
    {{-- Pagination --}}
    {{-- <div>
        <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded">
            <div class="card-body">
                <form action="#" method="GET">
                    <div class="row pb-3">
                        <div class="col-md-6 mb-3 mb-sm-0">
                            <select class="form-select" id="title_filter" name="title_filter" onchange="this.form.submit()">
                                @foreach ($titles as $t)
                                    <option value="{{ $t->id }}"
                                        {{ request('title_filter') == $t->id ? 'selected' : '' }}>
                                        {{ $t->group }} - {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3 mb-sm-0">
                            <select class="form-select" id="unit_filter" name="unit_filter" onchange="this.form.submit()">
                                <option value="all"
                                    {{ request('unit_filter') == 'all' || !request('unit_filter') ? 'selected' : '' }}>---หน่วยนับ---
                                </option>
                                {{-- @foreach ($equipment_units where() as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ request('unit_filter') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach --}}
                                @foreach ($equipments->where('title_id', request('title_filter'))->unique('equipment_unit_id') as $unit)
                                <option value="{{ $unit->equipment_unit_id }}"
                                    {{ request('unit_filter') == $unit->equipment_unit_id ? 'selected' : '' }}>
                                    {{ $unit->equipmentUnit->name}}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-sm-0">
                            <select class="form-select" id="location_filter" name="location_filter" onchange="this.form.submit()">
                                <option value="all"
                                    {{ request('location_filter') == 'all' || !request('location_filter') ? 'selected' : '' }}>---สถานที่ทั้งหมด---
                                </option>
                                @foreach ($equipments->where('title_id', request('title_filter'))->unique('location_id') as $location)
                                <option value="{{ $location->location_id }}"
                                    {{ request('location_filter') == $location->location_id ? 'selected' : '' }}>
                                    @if($location->location_id == null)
                                        ---ไม่ได้กำหนดสถานที่---
                                    @else
                                        {{ $location->location->name}}
                                    @endif
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-sm-0">
                            <select class="form-select" id="user_filter" name="user_filter" onchange="this.form.submit()">
                                <option value="all"
                                    {{ request('user_filter') == 'all' || !request('user_filter') ? 'selected' : '' }}>---ผู้ดูแลทั้งหมด---
                                </option>
                                @foreach ($equipments->where('title_id', request('title_filter'))->unique('user_id') as $user)
                                <option value="{{ $user->user_id }}"
                                    {{ request('user_filter') == $user->user_id ? 'selected' : '' }}>
                                    @if($user->user_id == null)
                                        ---ไม่ได้กำหนดผู้ดูแล---
                                    @else
                                        {{$user->user->prefix->name}}{{ $user->user->firstname }}  {{$user->user->lastname}}
                                    @endif
                                </option>
                            @endforeach
                            </select>
                        </div>
                </form>
            </div>
            <div class="row">
                <form onsubmit="searchTable(); return false;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label for="equipments-search" class="form-label">ค้นหา</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="equipments-search">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row mt-3">
                <div class="d-flex justify-content-between mb-3">  
                    {{-- @if(request()->query('bin_mode') == 1) --}}
                    <button id="restoreFromTrashBtn" class="btn btn-primary btn-sm mb-3 {{request()->query('bin_mode') == 1 ? '' : 'd-none'}}">
                        <i class="fas fa-trash"></i> ย้ายออกจากถังขยะ
                    </button>
                    {{-- @else --}}
                    <button id="moveToTrashBtn" class="btn btn-danger btn-sm mb-3  {{request()->query('bin_mode') == 1 ? 'd-none' : ''}}">
                        <i class="fas fa-trash"></i> ย้ายไปที่ถังขยะ
                    </button>  
                    {{-- @endif           --}}
                    <button class="btn btn-danger mb-3" it="goToBinBtn" onclick="goToBinMode()">
                        โหมดถังขยะ
                    </button>
                    <a href="{{route('equipment.create')}}" class="btn btn-success mb-3">เพิ่มข้อมูล</a>
                    <a href="/export" class="btn btn-success mb-3">
                        Export Excel
                    </a>
                </div>
            </div>
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th class="border align-middle" rowspan="2">
                            <div class="form-check d-flex justify-content-center align-items-center"
                                style="height: 100%;">
                                <input class="form-check-input" type="checkbox" id="select-all"
                                    style="transform: scale(1.5);">
                            </div>
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
                        <th class="border align-middle" rowspan="2">วันที่แก้</th>
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
                        

                        function filterBySelected($equipments, $titleId, $unitId, $locationId, $userId)
                        {
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
                            return $filtered;
                        }

                        // $eqs = Equipment::withTrashed()->find($id);
                        // dd($equipment_trash);

                        $eqs = $equipments;

                        $binMode  = request()->query('bin_mode');
                        if($binMode == 1){
                            $eqs = $equipment_trash;           
                         }
                        $filterEquipments = filterBySelected($eqs, $titleId, $unitId, $locationId, $userId);

                        $displayedTypes = [];
                    @endphp
                    @forelse ($filterEquipments as $key => $equipment)
                        @php
                            $type = $equipment->equipment_type_id;
                        @endphp
                        @if($type !== null)
                            @if(in_array($type, $displayedTypes))
                                @php
                                    continue;
                                @endphp
                            @endif

                            <tr class="text-center border border-dark">
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle"><br>
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">{{ $equipment->equipmentType->name }}
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">{{ $equipment->equipmentType->amount }}
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">{{ $equipment->equipmentType->price }}
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                    {{ $equipment->equipmentType->total_price }}
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">{{ $equipment->equipmentType->updated_at }}
                                </td>
                                <td class="bg-success text-white border border-dark align-middle">{{ $equipment->equipmentType->created_at }}
                                </td>
                            </tr>   

                            @forelse ($filterEquipments as $key => $equipment)
                                @if($equipment->equipment_type_id === $type)
                                <tr class="text-center border border-dark">
                                    <td class="bg-warning border border-dark align-middle">
                                        <div class="form-check d-flex justify-content-center align-items-center"
                                            style="height: 100%;">
                                            <input class="form-check-input checkbox-item" value="{{$equipment->id}}" type="checkbox"
                                                style="transform: scale(1.5);">
                                        </div>
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ ++$count }}<br>
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->number }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->name }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->equipmentUnit->name }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->amount }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->price }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        {{ $equipment->total_price }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_found }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_not_found }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_broken }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_disposal }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_transfer }}
                                    </td>
                                    <td class="bg-warning border border-dark text-start" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                        <p><span class="text-muted">ผู้ดูแล:</span> {{$equipment->user?->prefix?->name}}{{ $equipment->user?->firstname }}</p><hr>
                                        <p><span class="text-muted">ที่อยู่: </span>{{$equipment->location?->name}}</p><hr>
                                        <p><span class="text-muted">คำอธิบาย: </span>{{$equipment->description}}</p>
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->updated_at }}
                                    </td>
                                    <td class="bg-warning border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->created_at }}
                                    </td>
                                </tr>   
                                @endif

                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                            @endforelse

                            @php
                                $displayedTypes[] = $type;
                            @endphp
                        @else
                        <tr class="text-center border border-dark">
                            <td class="border border-dark align-middle">
                                <div class="form-check d-flex justify-content-center align-items-center"
                                    style="height: 100%;">
                                    <input class="form-check-input checkbox-item" value="{{$equipment->id}}" type="checkbox"
                                        style="transform: scale(1.5);">
                                </div>
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ ++$count }}<br>
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->number }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->name }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                {{ $equipment->equipmentUnit->name }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->amount }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->price }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                {{ $equipment->total_price }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_found }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_not_found }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_broken }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_disposal }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->status_transfer }}
                            </td>
                            <td class="border border-dark text-start"  onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">
                                <p><span class="text-muted">ผู้ดูแล:</span> {{$equipment->user?->prefix?->name}}{{ $equipment->user?->firstname }}</p><hr>
                                <p><span class="text-muted">ที่อยู่: </span>{{$equipment->location?->name}}</p><hr>
                                <p><span class="text-muted">คำอธิบาย: </span>{{$equipment->description}}</p>
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->updated_at }}
                            </td>
                            <td class="border border-dark align-middle" onclick="window.location='{{ route('equipment.edit', $equipment->id) }}'">{{ $equipment->created_at }}
                            </td>
                    </tr>   
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">ไม่มีข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

</x-layouts.app>
