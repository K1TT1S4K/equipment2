<x-layouts.app>
    <h3 class="text-dark">แก้ไขข้อมูลเอกสาร</h3>

    <!-- Nav tabs -->
    {{-- <ul class="nav nav-tabs mb-3" id="equipmentTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->get('page') < 1 ? 'active' : '' }}" id="edit-document-tab"
                data-bs-toggle="tab" data-bs-target="#edit-document" type="button" role="tab"
                aria-controls="edit-document" aria-selected="{{ request()->get('page') < 1 ? 'true' : 'false' }}">
                แก้ไขข้อมูล
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->get('page') >= 1 ? 'active' : '' }}" id="edit-related-equipment-tab"
                data-bs-toggle="tab" data-bs-target="#edit-related-equipment" type="button" role="tab"
                aria-controls="edit-related-equipment"
                aria-selected="{{ request()->get('page') > 1 ? 'true' : 'false' }}">
                แก้ไขข้อมูลครุภัณฑ์ที่เกี่ยวข้อง
            </button>
        </li>
    </ul> --}}

    <div class="tab-pane fade {{ request()->get('page') < 1 ? 'show active' : '' }}" id="edit-document" role="tabpanel"
        aria-labelledby="edit-tab">
        {{-- card สำหรับฟอร์มแก้ไขข้อมูลเอกสาร --}}
        <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark mt-4">
            <div class="card-body">
                <!-- ฟอร์มแก้ไขข้อมูล -->
                <form action="{{ route('document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">ประเภทเอกสาร <span
                                class="text-danger">*</span></label>
                        {{-- <select name="document_type" class="form-select" required>
                                <option value="ยื่นแทงจำหน่ายครุภัณฑ์"
                                    {{ $document->document_type == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                                    ยื่นแทงจำหน่ายครุภัณฑ์</option>
                                <option value="แทงจำหน่ายครุภัณฑ์"
                                    {{ $document->document_type == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                                    แทงจำหน่ายครุภัณฑ์
                                </option>
                                <option value="โอนครุภัณฑ์"
                                    {{ $document->document_type == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                                    โอนครุภัณฑ์</option>
                                <option value="ไม่พบ" {{ request('document_type') == 'ไม่พบ' ? 'selected' : '' }}>
                                    ไม่พบ</option>
                                <option value="ชำรุด" {{ request('document_type') == 'ชำรุด' ? 'selected' : '' }}>
                                    ชำรุด</option>
                            </select> --}}
                        <select id="document_type" name="document_type" class="form-select"
                            {{ Auth::user()->user_type == 'ผู้ดูแลครุภัณฑ์' || (Auth::user()->user_type == 'เจ้าหน้าที่พัสดุ' && $document->document_type == 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด') || (Auth::user()->user_type == 'ผู้ปฏิบัติงานบริหาร' && ($document->document_type == 'โอนครุภัณฑ์' || $document->document_type == 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี')) ? 'disabled' : '' }}>
                            <option value="">-- เลือกประเภทเอกสาร --</option>
                            @can('admin-or-branch')
                                <option value="สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี"
                                    {{ $document->document_type == 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี' ? 'selected' : '' }}>
                                    สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี</option>
                            @endcan
                            @can('admin-or-officer')
                                <option value="รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด"
                                    {{ $document->document_type == 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด' ? 'selected' : '' }}>
                                    รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด
                                </option>
                            @endcan
                            @can('admin-or-branch')
                                <option value="โอนครุภัณฑ์"
                                    {{ $document->document_type == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                                    โอนครุภัณฑ์</option>
                            @endcan
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">วันที่ดำเนินการ <span
                                class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ $document->date }}" required
                            {{ Auth::user()->user_type == 'ผู้ดูแลครุภัณฑ์' || (Auth::user()->user_type == 'เจ้าหน้าที่พัสดุ' && $document->document_type == 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด') || (Auth::user()->user_type == 'ผู้ปฏิบัติงานบริหาร' && ($document->document_type == 'โอนครุภัณฑ์' || $document->document_type == 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี')) ? 'disabled' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="newFile" class="form-label">เอกสารอ้างอิง
                            pdf</label>
                        <input type="file" name="newFile" class="form-control" accept=".pdf"
                            {{ Auth::user()->user_type == 'ผู้ดูแลครุภัณฑ์' || (Auth::user()->user_type == 'เจ้าหน้าที่พัสดุ' && $document->document_type == 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด') || (Auth::user()->user_type == 'ผู้ปฏิบัติงานบริหาร' && ($document->document_type == 'โอนครุภัณฑ์' || $document->document_type == 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี')) ? 'disabled' : '' }}>
                        <label>ไฟล์ปัจจุบัน: </label>

                        @if ($document && $document->stored_name)
                            <a href="{{ route('documents.download', $document->stored_name) }}">
                                {{ $document->original_name }}
                            </a>
                        @else
                            -
                        @endif
                    </div>

                    @if (
                        !(Auth::user()->user_type == 'ผู้ดูแลครุภัณฑ์' ||
                            (Auth::user()->user_type == 'เจ้าหน้าที่พัสดุ' &&
                                $document->document_type == 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด') ||
                            (Auth::user()->user_type == 'ผู้ปฏิบัติงานบริหาร' &&
                                ($document->document_type == 'โอนครุภัณฑ์' ||
                                    $document->document_type == 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี'))
                        ))
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        // เพิ่มครุภัณฑ์ที่เกี่ยวข้อง
        (function() {
            let docId = {{ $document->id }};

            document.getElementById('add-equipment-btn').addEventListener('click', function() {
                // ลบฟอร์มเก่าถ้ามี
                let oldForm = document.getElementById('equipment-card-form');
                if (oldForm) oldForm.remove();

                // หาตำแหน่งตาราง
                let tableContainer = document.getElementById('equipment-table-container');
                // 👉 ให้คุณ wrap ตารางด้วย div#equipment-table-container ใน Blade

                // สร้าง Card Form
                let card = document.createElement('div');
                card.classList.add('card', 'mb-3');
                card.id = 'equipment-card-form';

                card.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">เพิ่มครุภัณฑ์</h5>
                <form method="POST" action="{{ route('equipments_documents.store') }}">
                    @csrf
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    
                    <div class="row mb-3">
                        <div class="col-9">
                            <label class="form-label">ครุภัณฑ์</label>
                            <select name="equipment_id" class="form-select" id="equipment-select">
                                <option value="">-- เลือกครุภัณฑ์ --</option>
                                @foreach ($equipments as $equipment)
                                    @continue($equipment->original_id)
                                    <option value="{{ $equipment->id }}" data-name="{{ $equipment->name }}">
                                        {{ $equipment->number }} - {{ $equipment->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1">
                            <label class="form-label">จำนวน</label>
                            <input type="number" name="amount" class="form-control" min="1" value="1">
                        </div>
                                            <div class="col-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary ">บันทึก</button>
                        <a href="{{ url()->current() }}?page=1" class="btn btn-secondary ms-2">ยกเลิก</a>
                    </div>
                    </div>
                </form>
            </div>
        `;

                // แทรก card ก่อนตาราง
                tableContainer.parentNode.insertBefore(card, tableContainer);

                // อัพเดตชื่อครุภัณฑ์อัตโนมัติ
                let select = card.querySelector('#equipment-select');
                let nameInput = card.querySelector('#equipment-name');
                select.addEventListener('change', function() {
                    let selected = this.options[this.selectedIndex];
                    nameInput.value = selected.getAttribute('data-name') || '';
                });
            });
        })();


        (function() {
            document.getElementById('select-all').addEventListener('click', function(event) {
                let checkboxes = document.querySelectorAll('.equipments_documents_checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                });
                toggleDeleteButtons();
            });

            let checkboxes = document.querySelectorAll('.equipments_documents_checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleDeleteButtons);
            });

            function toggleDeleteButtons() {
                let selectedCheckboxes = document.querySelectorAll('.equipments_documents_checkbox:checked');
                let deleteSelectedBtn = document.getElementById('delete-selected-btn');
                let deleteAllBtn = document.getElementById('delete-all-btn');

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
        })();
    </script>
</x-layouts.app>
