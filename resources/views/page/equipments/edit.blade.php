<x-layouts.app>
    <h3 class="text-dark mb-3">แก้ไขข้อมูลครุภัณฑ์</h3>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="equipmentTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button"
                role="tab" aria-controls="edit" aria-selected="true">
                แก้ไขข้อมูล
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="document-tab" data-bs-toggle="tab" data-bs-target="#document" type="button"
                role="tab" aria-controls="document" aria-selected="false">
                การดำเนินการ
            </button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="equipmentTabContent">
        <!-- แก้ไขข้อมูล -->
        <div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="card shadow-lg p-3 mb-3 bg-body rounded border border-dark">
                <div class="card-body">
                    <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                        <input type="hidden" id="currentEquipmentUnitId" value="{{ $equipment->equipment_unit_id }}">
                        <input type="hidden" id="currentEquipmentTitleId" value="{{ $equipment->title_id }}">
                        <input type="hidden" id="currentEquipmentTypeId" value="{{ $equipment->equipment_type_id }}">
                        <input type="hidden" id="currentEquipmentLocationId" value="{{ $equipment->location_id }}">





                        <div class="row">
                            <div class="col-3 mb-3">
                                <div class="h-100 p-2 text-start" style="height:100%;">
                                    <!-- ซ่อน input ปกติ -->
                                    <input type="file" name="image" id="image" accept="image/*"
                                        style="display:none">

                                    <!-- ใช้ img เป็นตัวแทน input -->
                                    <img id="preview"
                                        src="{{ $equipment->image ? asset('storage/' . $equipment->image) : 'https://png.pngtree.com/png-clipart/20200225/original/pngtree-image-upload-icon-photo-upload-icon-png-image_5279794.jpg' }}"
                                        alt="คลิกเพื่อเปลี่ยนรูป" class="inputImage" <!-- รูปขนาดใหญ่สำหรับ hover -->
                                    <img id="hoverPreview"
                                        src="{{ $equipment->image ? asset('storage/' . $equipment->image) : 'https://png.pngtree.com/png-clipart/20200225/original/pngtree-image-upload-icon-photo-upload-icon-png-image_5279794.jpg' }}"
                                        class="bigImage">
                                </div>
                            </div>
                            <!-- คอลัมน์ซ้าย: A เรียงแนวตั้ง -->
                            <div class="col-9 d-flex flex-column">
                                <div class="mb-3"> <label class="form-label">หมายเลขครุภัณฑ์ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="number" class="form-control"
                                        value="{{ $equipment->number }}" required>
                                </div>
                                <div class="mb-3"><label class="form-label">ชื่อครุภัณฑ์ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ $equipment->name }}">
                                </div>
                                <div class="mb-3"> <label for="equipment_unit_id" class="form-label">หน่วยนับ <span
                                            class="text-danger">*</span>
                                        <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                            data-bs-toggle="modal" data-bs-target="#unitModal">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                    </label>
                                    <select name="equipment_unit_id" id="unitSelect" class="form-control" required>
                                        @foreach ($equipment_units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $equipment->equipment_unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-6"> <label class="form-label">จำนวน <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" required
                                    value="{{ $equipment->amount }}">
                            </div>
                            <div class="mb-3 col-6"> <label class="form-label">ราคา <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control"
                                    value="{{ $equipment->price }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col"> <label for="title_id" class="form-label">หัวข้อ <span
                                        class="text-danger">*</span>
                                    @can('admin-or-branch')
                                        <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                            data-bs-toggle="modal" data-bs-target="#titleModal">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                    @endcan
                                </label>
                                <select name="title_id" id="titleSelect" class="form-control" required>
                                    {{-- <option value="">-- เลือกหัวข้อ --</option> --}}
                                    @foreach ($titles as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $equipment->title_id == $t->id ? 'selected' : '' }}>{{ $t->group }}
                                            -
                                            {{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col"> <label for="equipment_type_id"
                                    class="form-label">ประเภท@can('admin-or-branch')
                                        <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                            data-bs-toggle="modal" data-bs-target="#typeModal">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                    @endcan
                                </label>
                                <select name="equipment_type_id" id="equipmentTypeSelect" class="form-control">
                                    {{-- <option value="">-- เลือกประเภท --</option> --}}
                                    <option value=""
                                        {{ $equipment->equipment_type_id == null ? 'selected' : '' }}>--
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
                                    <option value="" {{ $equipment->user_id == null ? 'selected' : '' }}>
                                        สาขาเทคโนโลยีคอมพิวเตอร์</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}"
                                            {{ $equipment->user_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->prefix->name }}{{ $u->firstname }}
                                            {{ $u->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">ที่อยู่@can('admin-or-branch')
                                        <button type="button" class="btn btn-sm btn-secondary ms-2 pt-0 pb-0 ps-1 pe-1"
                                            data-bs-toggle="modal" data-bs-target="#locationModal">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                    @endcan
                                </label>
                                <select name="location_id" class="form-control" id="locationSelect">
                                    <option value="" {{ $equipment->location_id == null ? 'selected' : '' }}>--
                                        เลือกที่อยู่ --</option>
                                    @foreach ($locations as $l)
                                        <option value="{{ $l->id }}"
                                            {{ $equipment->location_id == $l->id ? 'selected' : '' }}>
                                            {{ $l->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col"> <label class="form-label">คำอธิบาย</label>
                                <textarea rows="4" cols="20" type="text" name="description" class="form-control">{{ $equipment->description }}</textarea>
                            </div>
                        </div>
                        <div class="text-end">
                            @can('admin-or-branch')
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- การดำเนินการ -->
        <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
            <div class="card shadow-lg p-3 mb-3 bg-body rounded border border-dark">
                <div class="card-body">
                    <table class="table mt-3 table-hover w-full">
                        <thead class="text-center table-dark align-middle">
                            <tr class="text-center">
                                <th class="align-middle">ประเภท</th>
                                <th class="align-middle">จำนวน</th>
                                <th class="align-middle">วันที่ดำเนินการ</th>
                                <th class="align-middle">เอกสาร</th>
                                {{-- <th class="align-middle">จัดการ</th> --}}
                            </tr>
                        </thead>
                        <tbody class="align-middle p-3">
                            {{-- ข้อมูลเอกสาร --}}
                            {{-- {{dd($equipment_documents)}} --}}
                            @forelse ($equipment_documents->where('equipment_id', $equipment->id) as $key => $equipment_document)
                                <tr class="text-center" style="cursor: pointer;">
                                    <td>
                                        {{ $equipment_document->document->document_type }}
                                    </td>
                                    <td>
                                        {{ $equipment_document->amount }}
                                    </td>
                                    @php
                                        $date = \Carbon\Carbon::parse($equipment_document->document->date)->locale(
                                            'th',
                                        );
                                        // $buddhistYear = $date->year + 543;
                                    @endphp
                                    <td>{{ $date->isoFormat('D MMM YYYY') }}</td>
                                    <td>
                                        @if ($equipment_document->document->stored_name)
                                            <a href="{{ asset('storage/' . $equipment_document->document->stored_name) }}"
                                                download="{{ $equipment_document->document->original_name }}">{{ $equipment_document->document->original_name }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
    @endcan


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
