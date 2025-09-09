<x-layouts.app>
    <h3 class="text-dark mb-4">สืบค้นกิจกรรม</h3>
    <form action="{{ route('activity.search') }}" method="GET" class="mb-3">
        <div class="d-flex">

            <input type="text" id="query" name="query" class="form-control shadow-lg p-2 mb-3 rounded"
                placeholder="ค้นหากิจกรรม" value="{{ request('query') }}">
            <select id="model" name="model" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
                <option value="">-- เลือกประเภทกิจกรรม --</option>
                <option value="ครุภัณฑ์" {{ request('model') == 'ครุภัณฑ์' ? 'selected' : '' }}>
                    ครุภัณฑ์</option>
                <option value="เอกสาร" {{ request('model') == 'เอกสาร' ? 'selected' : '' }}>
                    เอกสาร</option>
                <option value="ผู้ใช้" {{ request('model') == 'ผู้ใช้' ? 'selected' : '' }}>
                    ผู้ใช้</option>
                <option value="ส่งออกข้อมูล" {{ request('model') == 'ส่งออกข้อมูล' ? 'selected' : '' }}>
                    ส่งออกข้อมูล</option>
            </select>


            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
        </div>
    </form>

    <div class="card shadow-lg p-3 mb-4 bg-body">
        <h3 class="mb-3">รายการกิจกรรม</h3>

        <table class="table table-hover w-full">
            <thead class="text-center table-dark align-middle">
                <tr class="text-center">
                    <th class="align-middle">ลำดับ</th>
                    <th class="align-middle">ผู้ดำเนินการ</th>
                    <th class="align-middle">กิจกรรม</th>
                    <th class="align-middle">รายละเอียด</th>
                    <th class="align-middle">วันที่แก้ไข</th>
                    <th class="align-middle">วันที่สร้าง</th>
                </tr>
            </thead>
            <tbody class="align-middle p-3">
                @forelse ($logs as $key => $log)
                    <tr class="text-center" style="cursor: pointer;">
                        <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                        <td>{{ $log->log_name }}</td>
                        <td class="text-center">{{ $log->menu }}</td>
                        <td class="text-start">
                            <pre>{{ json_encode($log->properties, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                        </td>
                        @php
                            $updated = \Carbon\Carbon::parse($log->updated_at)->locale('th');
                            $created = \Carbon\Carbon::parse($log->created_at)->locale('th');
                        @endphp
                        <td class="text-center">{{ $updated->isoFormat('D MMM') }} {{ $updated->year + 543 }}
                            {{ $updated->format('H:i:s') }}</td>
                        <td class="text-center">{{ $created->isoFormat('D MMM') }} {{ $created->year + 543 }}
                            {{ $created->format('H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">ไม่พบข้อมูล
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
</x-layouts.app>
