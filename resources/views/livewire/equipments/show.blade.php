<x-layouts.app>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded">
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-12">ครุภัณฑ์</div> --}}
                <div class="search-box">
                    <h6>ค้นหา</h6>
                    <div class="col-md-12">
                        <input type="text" name="query" class="form-control" placeholder="ค้นหา...">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-3 mb-3 mb-sm-0">
                    <form action="#">
                        <div class="mb-3">
                            <label for="equipment_statuses" class="form-label">ประวัติ</label>
                            <select class="form-select" id="equipment_statuses" name="equipment_statuses">
                                <option value="">-- กรุณาเลือก --</option>
                                {{-- @foreach ($equipment_statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 mb-3 mb-sm-0">
                    <form action="#">
                        <div class="mb-3">
                            <label for="unit" class="form-label">หน่วยนับ</label>
                            <select class="form-select" id="unit" name="unit">
                                <option value="">-- กรุณาเลือก --</option>
                                {{-- @foreach ($equipment_units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 mb-3 mb-sm-0">
                    <form action="#">
                        <div class="mb-3">
                            <label for="storage" class="form-label">ประเภทสถานที่จัดเก็บ</label>
                            <select class="form-select" id="storage" name="storage">
                                <option value="">-- กรุณาเลือก --</option>
                                {{-- @foreach ($storages as $storage)
                                    <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 mb-3 mb-sm-0">
                    <form action="#">
                        <div class="mb-3">
                            <label for="user" class="form-label">ประเภทสถานที่จัดเก็บ</label>
                            <select class="form-select" id="user" name="user">
                                <option value="">-- กรุณาเลือก --</option>
                                {{-- @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->fname }} {{ $user->lname }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </div>
    <div>
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
                            <th class="border align-middle">ราคารวม</th>
                            {{-- <th class="border align-middle">กลุ่ม</th> --}}
                            <th class="border align-middle">ประเภท</th>
                            <th class="border align-middle">สถานะ</th>
                            <th class="border align-middle">หัวข้อ</th>
                            <th class="border align-middle">หมายเหตุ</th>
                            <th class="border align-middle">สถานที่</th>
                            <th class="border align-middle">ผู้ดูแลครุภัณฑ์</th>
                            {{-- <th class="border align-middle">วันที่แก้ไข</th>
                            <th class="border align-middle">วันที่สร้าง</th> --}}
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
                                <td class="border border-dark align-middle">{{ $equipment->total_price }}</td>
                                {{-- <td class="border border-dark align-middle">{{ $equipment->group }}</td> --}}
                                <td class="border border-dark align-middle">{{ optional($equipment->equipmentType)->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->status }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->title->name }}</td>
                                <td class="border border-dark align-middle">{{ $equipment->description }}</td>
                                <td class="border border-dark align-middle">{{ optional($equipment->location)->name }}</td>
                                <td class="border border-dark align-middle">{{ optional($equipment->user)->name }}</td>
                                {{-- <td class="border border-dark align-middle">{{ $item->updated_at }}</td>
                                <td class="border border-dark align-middle">{{ $item->created_at }}</td> --}}
                                <td class="border border-dark align-middle">
                                    <!-- ปุ่มเปิดฟอร์มแก้ไข -->
                                    {{-- <button @click="open = true; equipmentToEdit = @json($item)"
                                        class="btn btn-warning">แก้ไข</button> --}}
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
    </div>

</x-layouts.app>
