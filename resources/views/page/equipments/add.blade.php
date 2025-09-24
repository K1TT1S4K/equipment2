<x-layouts.app>
    <h3 class="text-dark">เพิ่มข้อมูลครุภัณฑ์</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">

                <div class="row">
                    <div class="col-3 mb-3">
                        <div class="h-100 p-2 text-center" style="height:100%;">
                            <!-- ซ่อน input ปกติ -->
                            <input type="file" name="image" id="image" accept="image/*" style="display:none">

                            <!-- ใช้ img เป็นตัวแทน input -->
                            <img id="preview" src="{{ asset('images/please_upload_image.png') }}"
                                alt="คลิกเพื่อเปลี่ยนรูป" class="inputImage p-3">
                            <img id="hoverPreview" src="{{ asset('images/please_upload_image.png') }}"
                                class="bigImage">
                        </div>
                    </div>
                    <!-- คอลัมน์ซ้าย: A เรียงแนวตั้ง -->
                    <div class="col-9 d-flex flex-column">
                        <div class="mb-3"> <label class="form-label">หมายเลขครุภัณฑ์ <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="number" class="form-control" required>
                        </div>
                        <div class="mb-3"> <label class="form-label">ชื่อครุภัณฑ์ <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-4"><label for="equipment_unit_id" class="form-label">หน่วยนับ <span
                                        class="text-danger">*</span>
                                    <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                        data-bs-toggle="modal" data-bs-target="#unitModal">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </label>
                                <select name="equipment_unit_id" id="unitSelect" class="form-control" required>
                                    <option value="">-- เลือกหน่วยนับ --</option>
                                    @foreach ($equipment_units as $unit)
                                        @continue($unit->is_locked == 1)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4"><label class="form-label">จำนวน <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" required value="0">
                            </div>
                            <div class="col-4"><label class="form-label">ราคา <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" required value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"> <label for="title_id" class="form-label">หัวข้อ <span
                                class="text-danger">*</span><button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#titleModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="title_id" id="titleSelect" class="form-control" required>
                            <option value="">-- เลือกหัวข้อ --</option>
                            @foreach ($titles as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4"> <label for="user_id" class="form-label">ผู้ดูแล</label>
                        <select name="user_id" class="form-control">
                            <option value="">สาขาเทคโนโลยีคอมพิวเตอร์</option>
                            @foreach ($users as $u)
                                @continue($u->is_locked == 1)
                                <option value="{{ $u->id }}">{{ $u->prefix->name }}{{ $u->firstname }}
                                    {{ $u->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4"> <label class="form-label">ที่อยู่<button type="button"
                                class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1" data-bs-toggle="modal"
                                data-bs-target="#locationModal">
                                <i class="bi bi-gear"></i>
                            </button></label>
                        <select name="location_id" id="locationSelect" class="form-control">
                            <option value="">-- เลือกที่อยู่ --</option>
                            @foreach ($locations as $l)
                            @continue($l->is_locked == 1)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-10"> <label class="form-label">คำอธิบาย</label>
                        <textarea rows="1" cols="20" type="text" name="description" class="form-control"></textarea>
                    </div>
                    <div class="col-2 text-end" style="padding-top: 30px"><button type="submit"
                            class="btn btn-primary">บันทึก</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
                </div>
                <div class="text-end">
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
<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    // คลิกที่รูปก็เหมือนคลิก input
    preview.addEventListener('click', () => {
        imageInput.click();
    });

    // เมื่อเลือกไฟล์ใหม่ ให้โชว์ preview ทันที
    imageInput.addEventListener('change', function() {
        const [file] = this.files;
        if (file) {
            preview.src = URL.createObjectURL(file);
            hoverPreview.src = URL.createObjectURL(file);
        }
    });

    // แสดงรูปใหญ่เมื่อ hover
    preview.addEventListener('mouseenter', () => {
        hoverPreview.style.display = 'block';
    });
    preview.addEventListener('mouseleave', () => {
        hoverPreview.style.display = 'none';
    });
</script>
