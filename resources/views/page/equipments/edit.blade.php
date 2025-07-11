<x-layouts.app>
<<<<<<< HEAD
<<<<<<< HEAD
    <h1 class="text-dark mb-3 w-90 mx-auto">แก้ไขข้อมูลครุภัณฑ์ | <a href="#log">ประวัติการแก้ไข</a></h1>
    <div class="card w-90 mx-auto shadow-lg p-3 mb-3 bg-body rounded border border-dark">
=======
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-dark mb-0">
            แก้ไขข้อมูลครุภัณฑ์ | <a href="#log">ประวัติการแก้ไข</a> | <a href="#document">การดำเนินการ</a>
        </h3>
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> กลับ
        </a>
    </div>

    <div class="card w-auto mx-auto shadow-lg p-3 mb-3 bg-body rounded border border-dark">
>>>>>>> 4f630470fca786ed4cca9159c74c23d822e61d20
=======
    <h3 class="text-dark">แก้ไขข้อมูลครุภัณฑ์ | <a href="#log">ประวัติการแก้ไข</a></h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-3 bg-body rounded border border-dark">
>>>>>>> parent of 3fe3f1b (ออกแบบหน้าเพิ่ม แก้ไขครุภัณฑ์ใหม่)
        <div class="card-body">
            <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col">
                        <label class="form-label">หมายเลขครุภัณฑ์ <span class="text-danger">*</span></label>
                        <input type="text" name="number" id="number" class="form-control"
                            value="{{ $equipment->number }}" required>
                    </div>
                    <div class="mb-3 col">
                        <label class="form-label">ชื่อครุภัณฑ์ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                            value="{{ $equipment->name }}">
                    </div>
                    <div class="mb-3 col">
<<<<<<< HEAD
                        <label for="equipment_unit_id" class="form-label">หน่วยนับ <span class="text-danger">*</span>
                            @can('admin-or-branch')
                                <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                    data-bs-toggle="modal" data-bs-target="#unitModal">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endcan
                        </label>
=======
                        <label for="equipment_unit_id" class="form-label">หน่วยนับ<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#unitModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
>>>>>>> parent of 814f366 (fix pagination)
                        <select name="equipment_unit_id" class="form-control" required>
                            @foreach ($equipment_units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ $equipment->equipment_unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-3"> <label class="form-label">จำนวน <span class="text-danger">*</span></label>
                        <input type="text" name="amount" class="form-control" required
                            value="{{ $equipment->amount }}">
                    </div>
                    <div class="mb-3 col-3"> <label class="form-label">ราคา <span class="text-danger">*</span></label>
                        <input type="text" name="price" class="form-control" value="{{ $equipment->price }}">
                    </div>
                    <div class="col"> <label class="form-label">พบ <span class="text-danger">*</span></label>
                        <input type="text" name="status_found" class="form-control" required 
                            value="{{ $equipment->status_found }}">
                    </div>
                    <div class="col"> <label class="form-label">ไม่พบ <span class="text-danger">*</span></label>
                        <input type="text" name="status_not_found" class="form-control" required 
                            value="{{ $equipment->status_not_found }}">
                    </div>
                    <div class="col"> <label class="form-label">ชำรุด <span class="text-danger">*</span></label>
                        <input type="text" name="status_broken" class="form-control" required 
                            value="{{ $equipment->status_broken }}">
                    </div>
                    <div class="col"> <label class="form-label">จำหน่าย <span class="text-danger">*</span></label>
                        <input type="text" name="status_disposal" class="form-control" required 
                            value="{{ $equipment->status_disposal }}">
                    </div>
                    <div class="col"> <label class="form-label">โอน <span class="text-danger">*</span></label>
                        <input type="text" name="status_transfer" class="form-control" required 
                            value="{{ $equipment->status_transfer }}">
                    </div>
                </div>
                <div class="row mb-3">
<<<<<<< HEAD
                    <div class="col"> <label for="title_id" class="form-label">หัวข้อ <span
                                class="text-danger">*</span>
                            @can('admin-or-branch')
                                <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                    data-bs-toggle="modal" data-bs-target="#titleModal">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endcan
                        </label>
=======
                    <div class="col"> <label for="title_id" class="form-label">หัวข้อ<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#titleModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
>>>>>>> parent of 814f366 (fix pagination)
                        <select name="title_id" id="titleSelect" class="form-control" required>
                            {{-- <option value="">-- เลือกหัวข้อ --</option> --}}
                            @foreach ($titles as $t)
                                <option value="{{ $t->id }}"
                                    {{ $equipment->title_id == $t->id ? 'selected' : '' }}>{{ $t->group }} -
                                    {{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
<<<<<<< HEAD
                    <div class="col"> <label for="equipment_type_id"
                            class="form-label">ประเภท@can('admin-or-branch')
                                <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                    data-bs-toggle="modal" data-bs-target="#typeModal">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endcan
                        </label>
=======
                    <div class="col"> <label for="equipment_type_id" class="form-label">ประเภท<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#typeModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
>>>>>>> parent of 814f366 (fix pagination)
                        <select name="equipment_type_id" id="equipmentTypeSelect" class="form-control">
                            {{-- <option value="">-- เลือกประเภท --</option> --}}
                            <option value="" {{ $equipment->equipment_type_id == null ? 'selected' : '' }}>--
                                เลือกประเภท --</option>
                            @foreach ($equipment_types as $et)
                                {{-- <option
                                    value="{{ $et->id }} {{ $equipment->equipment_type_id == $et->id ? 'selected' : 'disabled' }}">
                                    {{ $et->name }}</option> --}}
                                @if ($equipment->title_id == $et->title_id)
                                    <option value="{{ $et->id }}"
                                        {{ $equipment->equipment_type_id == $et->id ? 'selected' : '' }}>
                                        {{ $et->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="user_id" class="form-label">ผู้ดูแล</label>
                        <select name="user_id" class="form-control">
                            {{-- <option value="">-- เลือกผู้ดูแล --</option> --}}
                            <option value="" {{ $equipment->user_id == null ? 'selected' : '' }}>-- เลือกผู้ดูแล
                                --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ $equipment->user_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->prefix->name }}{{ $u->firstname }}
                                    {{ $u->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
<<<<<<< HEAD
                        <label class="form-label">ที่อยู่@can('admin-or-branch')
                                <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                    data-bs-toggle="modal" data-bs-target="#locationModal">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endcan
                        </label>
=======
                        <label class="form-label">ที่อยู่<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#locationModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
>>>>>>> parent of 814f366 (fix pagination)
                        <select name="location_id" class="form-control">
                            <option value="" {{ $equipment->location_id == null ? 'selected' : '' }}>--
                                เลือกที่อยู่ --</option>
                            @foreach ($locations as $l)
                                <option value="{{ $l->id }}"
                                    {{ $equipment->location_id == $l->id ? 'selected' : '' }}>{{ $l->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col"> <label class="form-label">คำอธิบาย</label>
                        <input type="text" name="description"
                            class="form-control"value="{{ $equipment->description }}">
                    </div>
                </div>
                @can('admin-or-branch')
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <a href="#" onclick="backToPage(event)" class="btn btn-secondary">ยกเลิก</a>
                @endcan
            </form>
        </div>
    </div>

<<<<<<< HEAD
<<<<<<< HEAD
    <h3 class="text-dark mb-3 w-90 mx-auto">ประวัติการแก้ไข | <a href="#">แก้ไขข้อมูลครุภัณฑ์</a></h3>
    <div class="card w-90 mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
=======
    <h3 class="text-dark" id="log">ประวัติการแก้ไข | <a href="#document">การดำเนินการ</a> | <a
            href="#">แก้ไขข้อมูลครุภัณฑ์</a></h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
>>>>>>> 4f630470fca786ed4cca9159c74c23d822e61d20
=======
    <h3 class="text-dark">ประวัติการแก้ไข | <a href="#">แก้ไขข้อมูลครุภัณฑ์</a></h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
>>>>>>> parent of 3fe3f1b (ออกแบบหน้าเพิ่ม แก้ไขครุภัณฑ์ใหม่)
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-4">ชื่อ</th>
                        <th class="col-2">เวลา</th>
                        <th class="col-6">การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs->where('equipment_id', $equipment->id)->sortByDesc('created_at') as $log)
                        <tr>
                            <td>{{ $log->user?->prefix?->name }}{{ $log->user?->firstname }}
                                {{ $log->user?->lastname }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td class="white-space-pre">{{ $log->action }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<<<<<<< HEAD
    <h3 class="text-dark" id="document">การดำเนินการ | <a href="#">แก้ไขข้อมูลครุภัณฑ์</a> | <a
            href="#log">ประวัติการแก้ไข</a></h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <div class="row">
                <div class="col h4">พบ: {{$equipment->status_found}}</div>
                <div class="col h4">ไม่พบ: {{$equipment->status_not_found}}</div>
                <div class="col h4">ชำรุด: {{$equipment->status_broken}}</div>
                <div class="col h4">แทงจำหน่าย: {{$equipment->status_disposal}}</div>
                <div class="col h4">โอน: {{$equipment->status_transfer}}</div>
                <div class="col"> <button type="submit" class="btn btn-success">เพิ่ม</button></div>
            </div>
            <hr>
            <table class="table">
                <thead>
                    <tr><th class="col">สถานะ</th>
                        <th class="col">ประภท</th>
                        <th class="col">จำนวน</th>
                        <th class="col">เวลาดำเนินการเอกสาร</th>
                        <th class="col">เอกสาร</th>
                         <th class="col">จัดการ</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    @can('admin-or-branch')
        <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="unitModalLabel">จัดการข้อมูลหน่วยนับ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button id="addunitRow" class="btn btn-success mb-3">เพิ่มหน่วยนับ</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อหน่วยนับ</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody id="unitTableBody">
                                {{-- โหลดข้อมูลด้วย JS --}}
                            </tbody>
                        </table>
                    </div>
=======
    <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="unitModalLabel">จัดการข้อมูลหน่วยนับ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button id="addunitRow" class="btn btn-success mb-3">เพิ่มหน่วยนับ</button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ชื่อหน่วยนับ</th>
                                <th>การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody id="unitTableBody">
                            {{-- โหลดข้อมูลด้วย JS --}}
                        </tbody>
                    </table>
>>>>>>> parent of 814f366 (fix pagination)
                </div>
            </div>
        </div>

        <div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="titleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="titleModalLabel">จัดการข้อมูลหัวข้อ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button id="addtitleRow" class="btn btn-success mb-3">เพิ่มหัวข้อ</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>กลุ่ม</th>
                                    <th>ชื่อหัวข้อ</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody id="titleTableBody">
                                {{-- โหลดข้อมูลด้วย JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="typeModalLabel">จัดการข้อมูลประเภท</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button id="addtypeRow" class="btn btn-success mb-3">เพิ่มประเภท</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อประเภท</th>
                                    <th>หัวข้อ</th>
                                    <th>หน่วยนับ</th>
                                    <th>จำนวน</th>
                                    <th>ราคาต่อหน่วย</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody id="typeTableBody">
                                {{-- โหลดข้อมูลด้วย JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="locationModalLabel">จัดการข้อมูลที่อยู่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button id="addlocationRow" class="btn btn-success mb-3">เพิ่มที่อยู่</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อที่อยู่</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody id="locationTableBody">
                                {{-- โหลดข้อมูลด้วย JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
    @endcan
=======
    </div>


>>>>>>> parent of 814f366 (fix pagination)

    @vite(['resources/js/pages/equipment_add.js'])
</x-layouts.app>
