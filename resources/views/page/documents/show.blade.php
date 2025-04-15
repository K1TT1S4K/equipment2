<x-layouts.app>
    <h3 class="text-dark mb-4">เอกสาร</h3>
    <div class="card shadow p-3 mb-4 bg-body border border-dark">
        <form action="{{ route('document.search') }}" method="GET">
            <div class="row">
                <div class="col-md mb-3 mb-sm-0">
                    <label for="query" class="form-label">ค้นหา</label>
                    <input type="text" id="query" name="query" class="form-control" placeholder="เอกสารอ้างอิง, ประเภทเอกสาร ฯลฯ" value="{{ request('query') }}">
                </div>

                <div class="col-md mb-3 mb-sm-0">
                    <label for="document_type" class="form-label">ประเภทเอกสาร</label>
                    <select id="document_type" name="document_type" class="form-select">
                        <option value="">-- เลือกประเภทเอกสาร --</option>
                        <option value="ยื่นแทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'ยื่นแทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>ยื่นแทงจำหน่ายครุภัณฑ์</option>
                        <option value="แทงจำหน่ายครุภัณฑ์" {{ request('document_type') == 'แทงจำหน่ายครุภัณฑ์' ? 'selected' : '' }}>แทงจำหน่ายครุภัณฑ์</option>
                        <option value="โอนครุภัณฑ์" {{ request('document_type') == 'โอนครุภัณฑ์' ? 'selected' : '' }}>โอนครุภัณฑ์</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
                <a href="{{ route('document.search') }}" class="btn btn-danger ms-2">ล้างการค้นหา</a>
            </div>
        </form>
    </div>

    <div class="card shadow p-3 mb-4 bg-body border border-dark">
        <div class="row">
            <div class="col-4"><h3>รายการเอกสาร</h3></div>
            <div class="col-4"></div>
            <div class="col-4">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div>
                        <!-- ปุ่มเพิ่มข้อมูล -->
                        <a href="{{route('document.create')}}" class="btn btn-success mb-3">เพิ่มข้อมูล</a>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover border border-secondary-subtle">
            <thead class="text-center table-dark align-middle">
                <tr class="text-center">
                    <th class="align-middle">ลำดับ</th>
                    <th class="align-middle">ประเภทเอกสาร</th>
                    <th class="align-middle">วันที่ดำเนินการ</th>
                    <th class="align-middle">เอกสารอ้างอิง</th>
                    <th class="align-middle">วันที่แก้ไข</th>
                    <th class="align-middle">วันที่สร้าง</th>
                    <th class="align-middle">จัดการ</th>
                </tr>
            </thead>
            <tbody class="align-middle p-3">
                @foreach ($documents as $key => $document)
                <tr class="text-center">
                    <td class="text-center">{{ $key + 1 }}</td>
                    {{-- <td class="text-center">{{ $document->id }}</td> --}}
                    <td>{{ $document->document_type }}</td>
                    <td class="text-center">{{ $document->date }}</td>
                    <td class="text-center">
                        @if ($document->path)
                            <a href="{{ asset('storage/' . $document->path) }}" download="{{ $document->path }}">
                                {{ $document->path }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $document->updated_at }}</td>
                    <td class="text-center">{{ $document->created_at }}</td>
                    <td>
                        <!-- ปุ่มแก้ไข -->
                        <a href="{{route('document.edit', $document->id)}}" class="btn btn-warning">แก้ไข</a>

                        <!-- ปุ่มลบ -->
                        <form action="{{ route('document.delete', $document->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจว่าต้องการลบเอกสารนี้?')">ลบ</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        {{-- {{$documents->links()}} --}}
    </div>
</x-layouts.app>
