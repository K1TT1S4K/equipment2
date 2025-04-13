<x-layouts.app>
    <h3 class="text-dark text-center">เพิ่มข้มูลครุภัณฑ์</h3>
    <div class="mb-3">
        <label for="number" class="form-label">รหัสครุภัณฑ์ *</label>
        <input type="text" class="form-control" id="number" name="number" placeholder="รหัสครุภัณฑ์" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">รายการครุภัณฑ์ *</label>
        <textarea class="form-control" id="name" name="name" placeholder="รายการครุภัณฑ์" required></textarea>
    </div>
    <div class="mb-3">
        <label for="unit" class="form-label">หน่วยนับ *</label>
        <select class="form-select" id="unit" name="unit">
            <option value="">-- เลือกหน่วยนับ --</option>
            @foreach($equipment_units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
            <option value="add_new" id="add-new-option">+ เพิ่มหน่วยนับใหม่</option>
        </select>
    </div>
</x-layouts.app>

{{-- <div id="add-unit-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">×</span>
        <h3>เพิ่มหน่วยนับใหม่</h3>
        <form id="add-unit-form" method="POST" action="{{ route('equipment_units.store') }}">
            @csrf
            <label for="new_unit_name">ชื่อหน่วยนับ</label>
            <input type="text" id="new_unit_name" name="name" required>
            <button type="submit">เพิ่ม</button>
        </form>
    </div>
</div> --}}

{{-- <script>
    document.getElementById('equipment_unit_id').addEventListener('change', function() {
        if (this.value === 'add_new') {
            document.getElementById('add-unit-modal').style.display = 'block';
        }
    });

    function closeModal() {
        document.getElementById('add-unit-modal').style.display = 'none';
    }
</script> --}}