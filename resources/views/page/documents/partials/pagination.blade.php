<div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
    <div class="text-muted small mb-2">
        Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
    </div>
    <div>
        {{ $documents->links() }}
    </div>
</div>
