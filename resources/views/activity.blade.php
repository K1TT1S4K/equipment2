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
                    <th class="align-middle" style="width: 3%">ลำดับ</th>
                    <th class="align-middle" style="width: 20%">ผู้ดำเนินการ</th>
                    <th class="align-middle" style="width: 10%;">กิจกรรม</th>
                    <th class="align-middle" style="width: 10%;">ประเภทข้อมูล</th>
                    <th class="align-middle" style="width: 31%;">รายละเอียด</th>
                    <th class="align-middle" style="width: 13%;">วันที่แก้ไข</th>
                    <th class="align-middle" style="width: 13%;">วันที่สร้าง</th>
                </tr>
            </thead>
            <tbody class="align-middle p-3">
                @forelse ($logs as $key => $log)
                    <tr class="text-center" style="cursor: pointer;">
                        <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                        <td>{{ $log->log_name }}</td>
                        <td>{{ $log->menu }}</td>
                        <td>{{ $log->description ?? '-' }}</td>
                        @php
                            $map = [
                                //equipment
                                'name' => '<strong>ชื่อ</strong>',
                                'type' => '<strong>ประเภท</strong>',
                                'description' => '<strong>คำอธิบาย</strong>',
                                'unit' => '<strong>หน่วยนับ</strong>',
                                'price' => '<strong>ราคา</strong>',
                                'title' => '<strong>หัวข้อ</strong>',
                                'user' => '<strong>ผู้ดูแล</strong>',
                                'amount' => '<strong>จำนวนทั้งหมด</strong>',
                                'number' => '<strong>หมายเลขครุภัณฑ์</strong>',
                                'location' => '<strong>ที่อยู่</strong>',
                                'status_found' => '<strong>พบ</strong>',
                                'status_not_found' => '<strong>ไม่พบ</strong>',
                                'status_broken' => '<strong>ชำรุด</strong>',
                                'status_disposal' => '<strong>จำหน่าย</strong>',
                                'status_transfer' => '<strong>โอน</strong>',
                                'total_price' => '<strong>ราคารวม</strong>',

                                //document
                                'original_name' => '<strong>ชื่อเอกสาร</strong>',
                                'stored_name' => '<strong>ชื่อเอกสารที่ใช้เก็บในระบบ</strong>',
                                'document_type' => '<strong>ประเภทเอกสาร</strong>',
                                'date' => '<strong>วันที่ดำเนินการ</strong>',

                                // user
                                'username' => '<strong>ชื่อผู้ใช้</strong>',
                                'firstname' => '<strong>ชื่อจริง</strong>',
                                'lastname' => '<strong>นามสกุล</strong>',
                                'user_type' => '<strong>ระดับผู้ใช้</strong>',
                                'prefix' => '<strong>คำนำหน้าชื่อ</strong>',
                                'email' => '<strong>อีเมล</strong>',
                            ];
                            if (!empty($log->properties)) {
                                if ($log->description == 'บุคลากร') {
                                    $ordered = $log->orderUserProperties();
                                } elseif ($log->description == 'เอกสาร') {
                                    $ordered = $log->orderDocumentProperties();
                                } elseif ($log->description == 'ครุภัณฑ์') {
                                    $ordered = $log->orderEquipmentProperties();
                                } else {
                                    $ordered = $log->properties;
                                }
                                // true หมายถึงแปลง json เป็น php array แทนที่จะแปลงเป็น object
                                $newProperties = json_decode($log->properties, true);
                                if (array_key_exists('ข้อมูลก่อนแก้ไข', $newProperties)) {
                                    $oldValues = [];
                                    $newValues = [];
                                    foreach ($ordered['ข้อมูลก่อนแก้ไข'] as $key => $value) {
                                        $key = $map[$key] ?? $key; // ถ้าไม่เจอ key ก็ใช้ค่าดั้งเดิม

                                        $oldValues[] = $key . ': ' . $value;
                                    }
                                    foreach ($ordered['ข้อมูลหลังแก้ไข'] as $key => $value) {
                                        $key = $map[$key] ?? $key; // ถ้าไม่เจอ key ก็ใช้ค่าดั้งเดิม

                                        $newValues[] = $key . ': ' . $value;
                                    }

                                    echo '<td class="text-start">' .
                                        '<strong>ข้อมูลก่อนแก้ไข</strong><br>' .
                                        implode(', ', $oldValues) .
                                        '<br>' .
                                        '<strong>ข้อมุลหลังแก้ไข</strong><br>' .
                                        implode(', ', $newValues) .
                                        '</td>';
                                } elseif ($log->properties == '[]') {
                                    echo '<td class="text-center">' . '-' . '</td>';
                                } else {
                                    $myArray = [];
                                    foreach ($ordered as $key => $value) {
                                        // dd($key, $map[$key]);

                                        $key = $map[$key] ?? $key; // ถ้าไม่เจอ key ก็ใช้ค่าดั้งเดิม

                                        $myArray[] = $key . ': ' . $value;
                                    }

                                    echo '<td class="text-start">' . implode(', ', $myArray) . '</td>';
                                }
                            }
                        @endphp
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
