<x-layouts.app>
    <h3 class="text-dark">เพิ่มข้อมูลครุภัณฑ์</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('equipment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                {{-- @php
    dd(url()->previous());
@endphp --}}
{{-- {{url()->previous()}} --}}
                <div class="row">
                    <div class="mb-3 col">
                        <label class="form-label">หมายเลขครุภัณฑ์ <span class="text-danger">*</span></label>
                        <input type="text" name="number" class="form-control" required>
                    </div>
                    <div class="mb-3 col">
                        <label class="form-label">ชื่อครุภัณฑ์ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3 col">
                        <label for="equipment_unit_id" class="form-label">หน่วยนับ <span class="text-danger">*</span><button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#unitModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="equipment_unit_id" class="form-control" required>
                            {{-- <option value="1" {{request('equipment_unit_id') == 1 ? 'selected' : '' }}>-- เลือกหน่วยนับ --</option> --}}
                            <option value="">-- เลือกหน่วยนับ --</option>
                            @foreach ($equipment_units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-3"> <label class="form-label">จำนวน <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" required value="0">
                    </div>
                    <div class="mb-3 col-3"> <label class="form-label">ราคา <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" required value="0">
                    </div>
                    <div class="col"> <label class="form-label">พบ <span class="text-danger">*</span></label>
                        <input type="number" name="status_found" class="form-control" required value="0">
                    </div>
                    <div class="col"> <label class="form-label">ไม่พบ <span class="text-danger">*</span></label>
                        <input type="number" name="status_not_found" class="form-control" required value="0">
                    </div>
                    <div class="col"> <label class="form-label">ชำรุด <span class="text-danger">*</span></label>
                        <input type="number" name="status_broken" class="form-control" required value="0">
                    </div>
                    <div class="col"> <label class="form-label">จำหน่าย <span class="text-danger">*</span></label>
                        <input type="number" name="status_disposal" class="form-control" required value="0">
                    </div>
                    <div class="col"> <label class="form-label">โอน <span class="text-danger">*</span></label>
                        <input type="number" name="status_transfer" class="form-control" required value="0">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col"> <label for="title_id" class="form-label">หัวข้อ <span class="text-danger">*</span><button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#titleModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="title_id" id="titleSelect" class="form-control" required>
                            <option value="">-- เลือกหัวข้อ --</option>
                            @foreach ($titles as $t)
                                <option value="{{ $t->id }}">{{ $t->group }} - {{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col"> <label for="equipment_type_id" class="form-label">ประเภท<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#typeModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="equipment_type_id" id="equipmentTypeSelect" class="form-control">
                            <option value="">-- เลือกประเภท --</option>
                            {{-- @foreach ($equipment_types as $et)
                                <option value="{{$et->id}}">{{$et->name}}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="user_id" class="form-label">ผู้ดูแล</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- เลือกผู้ดูแล --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->prefix->name }}{{ $u->firstname }}
                                    {{ $u->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">ที่อยู่<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#locationModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="location_id" class="form-control">
                            <option value="">-- เลือกที่อยู่ --</option>
                            @foreach ($locations as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col"> <label class="form-label">คำอธิบาย</label>
                        <textarea rows="4" cols="20" type="text" name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="text-end"> <button type="submit" class="btn btn-primary">บันทึก</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>

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

</x-layouts.app>
