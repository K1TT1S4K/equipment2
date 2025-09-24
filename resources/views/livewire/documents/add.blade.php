<!-- Modal เพิ่มข้อมูล -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">เพิ่มข้อมูลเอกสาร</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ฟอร์มเพิ่มข้อมูล -->
                <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="document_type" class="form-label">เอกสาร</label>
                        <select name="document_type" class="form-select">
                            <option value="">-- เลือกประเภทเอกสาร --</option>
                            <option value="ยื่นแทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>ยื่นแทงจำหน่ายครุภัณฑ์</option>
                            <option value="แทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์</option>
                            <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>โอนครุภัณฑ์</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">วันที่ดำเนินการ</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">เอกสารอ้างอิง</label>
                        <input type="file" class="form-control" id="document" name="document" required>
                        {{-- <input type="file" class="form-control" id="document" name="document" required> --}}
                      </div>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</div>
