<x-layouts.app>
    <h1 class="text-dark">เพิ่มเอกสาร</h1>
    <div class="card w-75 mx-auto shadow-lg p-3 mb-4 rounded border border-dark mt-4">
        <div class="card-body">
            <!-- ฟอร์มเพิ่มข้อมูล -->
            <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="document_type" class="form-label">เอกสาร <span class="text-danger">*</span></label>
                    <select name="document_type" class="form-select" id="document_type" required>
                        <option value="">-- เลือกประเภทเอกสาร --</option>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์"
                            {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์"
                            {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์
                        </option>
                        <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                            โอนครุภัณฑ์</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">วันที่ดำเนินการ <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">เอกสารอ้างอิง <span class="text-danger">*</span> (PDF, DOC, DOCX)</label>
                    <input type="file" class="form-control" id="document" name="document" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <a href="{{ route('document.index') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
