<x-layouts.app>
    <h3 class="text-dark">เอกสาร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-4 bg-body rounded border border-dark mt-4">
        <div class="card-body">
            <!-- ฟอร์มแก้ไขข้อมูล -->
            <form action="{{ route('document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="document_type" class="form-label">ประเภทเอกสาร</label>
                    <select name="document_type" class="form-select" required>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์"
                            {{ $document->document_type == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>
                            ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์"
                            {{ $document->document_type == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์
                        </option>
                        <option value="โอนครุภัณฑ์" {{ $document->document_type == 'โอนครุภัณฑ์' ? 'selected' : '' }}>
                            โอนครุภัณฑ์</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">วันที่ดำเนินการ</label>
                    <input type="date" name="date" class="form-control" value="{{ $document->date }}" required>
                </div>

                <div class="mb-3">
                    <label for="newFile" class="form-label">อัปโหลดไฟล์เอกสาร (PDF, DOC, DOCX)</label>
                    <input type="file" name="newFile" class="form-control" accept=".pdf,.doc,.docx">
                    @if ($document && $document->path)
                        <small class="form-text text-muted">ไฟล์เดิม: <a href="{{ url('storage/' . $document->path) }}"
                                download>{{ basename($document->path) }}</a></small>
                    @endif
                </div>
                @can('admin-or-branch')
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <a href="{{ route('document.index') }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
                @endcan

                @if($document->document_type == 'แทงจำหน่ายครุภัณฑ์')
                @can('officer')
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <a href="{{ route('document.index') }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
                @endcan
                @endif
            </form>
        </div>
    </div>
</x-layouts.app>
