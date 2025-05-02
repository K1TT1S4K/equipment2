<!-- Modal แก้ไขเอกสาร -->
<div wire:ignore.self class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขเอกสาร</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="updateDocument">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="document_type" class="form-label">ประเภทเอกสาร</label>
                        <select wire:model="document_type" class="form-select" required>
                            <option value="ยื่นแทงจำหน่ายครุภัณฑ์">ยื่นแทงจำหน่ายครุภัณฑ์</option>
                            <option value="แทงจำหน่ายครุภัณฑ์">แทงจำหน่ายครุภัณฑ์</option>
                            <option value="โอนครุภัณฑ์">โอนครุภัณฑ์</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">วันที่ดำเนินการ</label>
                        <input type="date" wire:model="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="newFile" class="form-label">อัปโหลดไฟล์เอกสาร (PDF, DOC, DOCX)</label>
                        <input type="file" wire:model="newFile" class="form-control" required>
                        {{-- @if ($document && $document->path)
                            <small class="form-text text-muted">ไฟล์เดิม: <a href="{{ asset('storage/' . $document->path) }}" download>{{ basename($document->path) }}</a></small>
                        @endif --}}
                        <div wire:loading wire:target="newFile">กำลังอัปโหลด...</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">อัปเดต</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script ปิด Modal อัตโนมัติหลังจากอัปเดต -->
<script>
    window.addEventListener('close-modal', event => {
        var modal = document.getElementById('editDocumentModal');
        var modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    });
</script>
