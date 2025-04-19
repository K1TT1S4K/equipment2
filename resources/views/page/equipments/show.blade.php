<x-layouts.app>
    <h3 class="text-dark">จัดการครุภัณฑ์</h3>
    <form action="{{ route('equipment.index') }}" method="GET" class="shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row">
            <div class="search-box">
                <h6>ค้นหา</h6>
                <div class="col-md-12">
                    <input type="text" name="query" class="form-control" placeholder="รหัสครุภัณฑ์, รายการ, สถานที่จัดเก็บ..." />
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <label for="unit" class="form-label">หน่วยนับ</label>
                <select class="form-select" id="unit" name="unit">
                    <option value="">-- เลือกหน่วยนับ --</option>
                    @foreach($equipment_units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="storage" class="form-label">สถานที่จัดเก็บ</label>
                <select class="form-select" id="storage" name="storage">
                    <option value="">-- เลือกสถานที่จัดเก็บ --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="user" class="form-label">ผู้ดูแลครุภัณฑ์</label>
                <select class="form-select" id="user" name="user">
                    <option value="">-- เลือกผู้ดูแลครุภัณฑ์ --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->prefix->name }} {{ $user->firstname }} {{ $user->lastname }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="btn btn-primary p-2">ค้นหา</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-danger">ล้างการค้นหา</a>
        </div>
    </form>

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
                <!-- ปุ่ม Export Excel -->
                <div class="d-flex justify-content-end mb-3">
                    <a href="#" class="btn btn-success">
                        Export Excel
                    </a>
                </div>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th class="border align-middle">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="border align-middle">#</th>
                            <th class="border align-middle">รหัสครุภัณฑ์</th>
                            <th class="border" style="width: 20%">รายการ <br>( ยี่ห้อ, ชนิด, แบบ, ขนาดและลักษณะ )</th>
                            <th class="border align-middle">หน่วยนับ</th>
                            <th class="border align-middle">จำนวน<br>คงเหลือ</th>
                            <th class="border">ราคาต่อหน่วย <br>(บาท)</th>
                            <th class="border align-middle">ราคารวม</th>\
                            <th class="border align-middle">ประเภท</th>
                            <th class="border align-middle">สถานะ</th>
                            <th class="border align-middle">หัวข้อ</th>
                            <th class="border align-middle">หมายเหตุ</th>
                            <th class="border align-middle">สถานที่</th>
                            <th class="border align-middle">ผู้ดูแลครุภัณฑ์</th>\
                            <th class="border align-middle">จัดการ</th>
                        </tr>

                    </thead>
                    <tbody>
                        @forelse ($equipments as $key => $equipment)
                            <tr class="text-center border border-dark">
                                <td class="border border-dark align-middle">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </td>
                                <td class="border border-dark align-middle">{{ $key + 1}} <br> ({{ $equipment->amount }})</td>
                                <td class="border border-dark align-middle">{{ $equipment->number}}</td>
                                <td class="border border-dark align-middle">{{ $equipment->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->equipmentUnit->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->amount }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->price }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->total_price }}</td>\
                                <td class="border border-dark align-middle">{{ optional($equipment->equipmentType)->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->status }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->title->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->description }}</td>
                                <td class="border border-dark align-middle">{{ optional($equipment->location)->name }}</td>
                                <td class="border border-dark align-middle">{{ optional($equipment->user)->name }}</td>
                                <td class="border border-dark align-middle">
                                    <!-- ปุ่มเปิดฟอร์มแก้ไข -->
                                    <button class="btn btn-warning">แก้ไข</button>

                                    <!-- ปุ่มลบ (จะใช้ฟอร์ม POST เพื่อป้องกันการใช้ GET method) -->
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE') <!-- ใช้ HTTP DELETE สำหรับการลบ -->
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('คุณต้องการลบข้อมูลนี้จริงหรือไม่?')">ลบ</button>
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
            </div>
        </div>
    </div> --}}

</x-layouts.app>
