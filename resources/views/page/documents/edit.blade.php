<x-layouts.app>
    <h3 class="text-dark">แก้ไขข้อมูลเอกสาร</h3>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="equipmentTab" role="tablist">
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
    </ul>

    @can('admin-or-branch')
        <div class="tab-content" id="documentTabContent">
            <div class="tab-pane fade {{ request()->get('page') < 1 ? 'show active' : '' }}" id="edit-document"
                role="tabpanel" aria-labelledby="edit-tab">
                {{-- card สำหรับฟอร์มแก้ไขข้อมูลเอกสาร --}}
                <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark mt-4">
                    <div class="card-body">
                        <!-- ฟอร์มแก้ไขข้อมูล -->
                        <form action="{{ route('document.update', $document->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                            <div class="mb-3">
                                <label for="document_type" class="form-label">ประเภทเอกสาร <span
                                        class="text-danger">*</span></label>
                                <select name="document_type" class="form-select" required>
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
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">วันที่ดำเนินการ <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ $document->date }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="newFile" class="form-label">เอกสารอ้างอิง
                                    pdf</label>
                                <input type="file" name="newFile" class="form-control" accept=".pdf">
                                @if ($document && $document->path)
                                    <small class="form-text text-muted">ไฟล์เดิม: <a
                                            href="{{ url('storage/' . $document->path) }}"
                                            download>{{ basename($document->path) }}</a></small>
                                @endif
                            </div>
                            @can('admin-or-branch')
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                                </div>
                            @endcan

                            @if ($document->document_type == 'แทงจำหน่ายครุภัณฑ์')
                                @can('officer')
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                                    </div>
                                @endcan
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ request()->get('page') >= 1 ? 'show active' : '' }}" id="edit-related-equipment"
                role="tabpanel" aria-labelledby="edit-related-equipment-tab">
                <form action="{{ route('equipments_documents.deleteSelected') }}" method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <div class="col-4">
                        <div>
                            <!-- ปุ่มลบทั้งหมด -->
                            <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                                style="display:none;">ลบรายการทั้งหมด</button>
                            <!-- ปุ่มลบที่เลือก -->
                            <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                                style="display:none;">ลบรายการที่เลือก</button>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-success mb-4" id="add-equipment-btn">
                            เพิ่มครุภัณฑ์ที่เกี่ยวข้อง
                        </button>
                    </div>
                    {{-- ตารางสำหรับเพิ่มข้อมูลครุภัณฑ์ที่เกี่ยวข้อง --}}
                    <div id="equipment-table-container">
                        <table class="table table-hover w-full">
                            <thead class="text-center table-dark align-middle">
                                <tr class="text-center">
                                    <th style="width: 5%;"><input type="checkbox" id="select-all"></th>
                                    <th class="align-middle" style="width:20%;">รหัสครุภัณฑ์</th>
                                    <th class="align-middle" style="width: 70%">รายการ</th>
                                    <th class="align-middle" style="width: 5%;">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle p-3" id="equipment-table-body">
                                @forelse ($equipments_documents as $key => $ed)
                                    <tr class="text-center" style="cursor: pointer;" onclick="window.location=''">
                                        <td onclick="event.stopPropagation();">
                                            <input type="checkbox" class="equipments_documents_checkbox"
                                                name="selected_equipments_documents[]" value="{{ $ed->id }}">
                                        </td>
                                        <td>{{ $ed->equipment->number }}</td>
                                        <td class="text-start">{{ $ed->equipment->name }}</td>
                                        <td>{{ $ed->amount }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">ไม่พบข้อมูล
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="d-flex justify-content-center">
                    {{ $equipments_documents->links() }}
                </div>
            </div>
        </div>
    @endcan

    @can('officer')
        <div class="tab-content" id="documentTabContent">
            <div class="tab-pane fade {{ request()->get('page') < 1 ? 'show active' : '' }}" id="edit-document"
                role="tabpanel" aria-labelledby="edit-tab">
                {{-- card สำหรับฟอร์มแก้ไขข้อมูลเอกสาร --}}
                <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark mt-4">
                    <div class="card-body">
                        <!-- ฟอร์มแก้ไขข้อมูล -->
                        <form action="{{ route('document.update', $document->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                            <div class="mb-3">
                                <label for="document_type" class="form-label">ประเภทเอกสาร <span
                                        class="text-danger">*</span></label>
                                <select name="document_type" class="form-select" required
                                    @if ($document->document_type != 'แทงจำหน่ายครุภัณฑ์') disabled @endif>
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
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">วันที่ดำเนินการ <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ $document->date }}"
                                    @if ($document->document_type != 'แทงจำหน่ายครุภัณฑ์') disabled @endif required>
                            </div>

                            <div class="mb-3">
                                <label for="newFile" class="form-label">เอกสารอ้างอิง
                                    pdf</label>
                                <input type="file" name="newFile" class="form-control" accept=".pdf"
                                    @if ($document->document_type != 'แทงจำหน่ายครุภัณฑ์') disabled @endif>
                                @if ($document && $document->path)
                                    <small class="form-text text-muted">ไฟล์เดิม: <a
                                            href="{{ url('storage/' . $document->path) }}"
                                            download>{{ basename($document->path) }}</a></small>
                                @endif
                            </div>

                            @if ($document->document_type == 'แทงจำหน่ายครุภัณฑ์')
                                @can('officer')
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                                    </div>
                                @endcan
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ request()->get('page') >= 1 ? 'show active' : '' }}" id="edit-related-equipment"
                role="tabpanel" aria-labelledby="edit-related-equipment-tab">
                <form action="{{ route('equipments_documents.deleteSelected') }}" method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    @if ($document->document_type == 'แทงจำหน่ายครุภัณฑ์')
                        <div class="col-4">
                            <div>
                                <!-- ปุ่มลบทั้งหมด -->
                                <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                                    style="display:none;">ลบรายการทั้งหมด</button>
                                <!-- ปุ่มลบที่เลือก -->
                                <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                                    style="display:none;">ลบรายการที่เลือก</button>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-success mb-4" id="add-equipment-btn">
                                เพิ่มครุภัณฑ์ที่เกี่ยวข้อง
                            </button>
                        </div>
                    @endif
                    {{-- ตารางสำหรับเพิ่มข้อมูลครุภัณฑ์ที่เกี่ยวข้อง --}}
                    <div id="equipment-table-container">
                        <table class="table table-hover w-full">
                            <thead class="text-center table-dark align-middle">
                                <tr class="text-center">
                                    <th style="width: 5%;"><input type="checkbox" id="select-all"></th>
                                    <th class="align-middle" style="width:20%;">รหัสครุภัณฑ์</th>
                                    <th class="align-middle" style="width: 70%">รายการ</th>
                                    <th class="align-middle" style="width: 5%;">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle p-3" id="equipment-table-body">
                                @forelse ($equipments_documents as $key => $ed)
                                    <tr class="text-center" style="cursor: pointer;" onclick="window.location=''">
                                        <td onclick="event.stopPropagation();">
                                            <input type="checkbox" class="equipments_documents_checkbox"
                                                name="selected_equipments_documents[]" value="{{ $ed->id }}">
                                        </td>
                                        <td>{{ $ed->equipment->number }}</td>
                                        <td class="text-start">{{ $ed->equipment->name }}</td>
                                        <td>{{ $ed->amount }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">ไม่พบข้อมูล
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="d-flex justify-content-center">
                    {{ $equipments_documents->links() }}
                </div>
            </div>
        </div>
    @endcan

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
