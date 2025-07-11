<x-layouts.app>
    <h1 class="text-dark w-90 bold mb-4">เอกสาร</h1>
    <div class="p-3 mb-4 w-75 justify-content-center mx-auto">
        <form id="search-form" onsubmit="return false;">
            <div class="row">
                <div class="col-md mb-3 mb-sm-0">
                    <input type="text" id="query" name="query" class="form-control border border-dark shadow-lg"
                        placeholder="ค้นหาเอกสารอ้างอิง, ประเภทเอกสาร ฯลฯ">
                </div>
                <div class="col-md mb-3 mb-sm-0">
                    <select id="document_type" name="document_type" class="form-select border border-dark shadow-lg">
                        <option value="">-- เลือกประเภทเอกสาร --</option>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์">ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์">แทงจำหน่ายครุภัณฑ์</option>
                        <option value="โอนครุภัณฑ์">โอนครุภัณฑ์</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="card bg-body p-3 mb-4 w-90 justify-content-center mx-auto">
        {{-- <h3>รายการเอกสาร</h3> --}}

        <!-- ย้าย form มาอยู่ตรงนี้ ครอบทั้งปุ่มลบและตาราง -->
        <form action="{{ route('document.deleteSelected') }}" method="POST" id="delete-form">
            @csrf
            @method('DELETE')

            <div class="row mb-1">
                <div class="col-8 align-self-center">
                    <h2>รายการเอกสาร</h2>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <!-- ปุ่มลบทั้งหมด -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-all-btn"
                            style="display:none;">ย้ายรายการทั้งหมดไปที่ถังขยะ</button>
                        <!-- ปุ่มลบที่เลือก -->
                        <button type="submit" class="btn btn-danger mb-3" id="delete-selected-btn"
                            style="display:none;">ย้ายไปที่ถังขยะ</button>
                    </div>
                </div>
                {{-- <div class="col-4"></div> --}}
                @can('admin-or-branch')
                    <div class="col-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div>
                                <!-- ปุ่มเพิ่มข้อมูล -->
                                <a href="{{ route('document.create') }}" class="btn btn-success mb-3">เพิ่มเอกสาร</a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <table class="documents-table w-100">
                <thead class="text-center align-middle">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ลำดับ</th>
                        <th>ประเภทเอกสาร</th>
                        <th>วันที่ดำเนินการ</th>
                        <th>เอกสารอ้างอิง</th>
                        <th>วันที่แก้ไข</th>
                        <th>วันที่สร้าง</th>
                    </tr>
                </thead>
                <tbody id="document-table-body" class="align-middle p-3">
                    @include('page.documents.partials.rows', ['documents' => $documents])
                </tbody>
                {{-- <tbody class="align-middle p-3">
                    @foreach ($documents as $key => $document)
                        <tr class="text-center" style="cursor: pointer;"
                            onclick="window.location='{{ route('document.edit', $document->id) }}'">
                            <td onclick="event.stopPropagation();">
                                <input type="checkbox" class="document-checkbox" name="selected_documents[]"
                                    value="{{ $document->id }}">
                            </td>
                            <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                            <td>{{ $document->document_type }}</td>
                            @php
                                $date = \Carbon\Carbon::parse($document->date);
                                $updated = \Carbon\Carbon::parse($document->updated_at);
                                $created = \Carbon\Carbon::parse($document->created_at);
                            @endphp
                            <td class="text-center">
                                {{ $date->format('j') }} {{ $date->locale('th')->translatedFormat('M') }}
                                {{ $date->year + 543 }}
                            </td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                @if ($document->path)
                                    <a href="{{ asset('storage/' . $document->path) }}"
                                        download="{{ $document->path }}">{{ $document->path }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $updated->format('j') }} {{ $updated->locale('th')->translatedFormat('M') }}
                                {{ $updated->year + 543 }}
                            </td>
                            <td class="text-center">
                                {{ $created->format('j') }} {{ $created->locale('th')->translatedFormat('M') }}
                                {{ $created->year + 543 }}
                            </td>
                        </tr>
                    @endforeach
                </tbody> --}}

            </table>
        </form>

        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>

        {{-- <div class="d-flex justify-content-center">
    {{$documents->links('vendor.livewire.task-paginate')}}
</div> --}}

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            function fetchDocuments() {
                let query = $('#query').val();
                let documentType = $('#document_type').val();

                $.ajax({
                    url: "{{ route('document.search') }}",
                    type: 'GET',
                    data: {
                        query: query,
                        document_type: documentType
                    },
                    success: function (data) {
                        $('#document-table-body').html(data);
                    },
                    error: function () {
                        $('#document-table-body').html(`<tr><td colspan="7" class="text-center text-danger">เกิดข้อผิดพลาด</td></tr>`);
                    }
                });
            }

            $('#query, #document_type').on('input change', fetchDocuments);

            $('#select-all').on('click', function () {
                $('.document-checkbox').prop('checked', this.checked);
                toggleDeleteButtons();
            });

            $(document).on('change', '.document-checkbox', toggleDeleteButtons);

            function toggleDeleteButtons() {
                let selected = $('.document-checkbox:checked').length;
                let total = $('.document-checkbox').length;

                $('#delete-selected-btn').toggle(selected > 0 && selected < total);
                $('#delete-all-btn').toggle(selected === total);
            }
        });
    </script>
    @endpush
</x-layouts.app>
