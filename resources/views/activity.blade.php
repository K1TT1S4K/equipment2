<x-layouts.app>
    <h3 class="text-dark mb-4">บันทึกกิจกรรม</h3>
    <form action="{{ route('activity.search') }}" method="GET" class="mb-3">
        <div class="d-flex">
            <div class="flex-grow-1"><input type="text" id="query" name="query"
                    class="form-control shadow-lg p-2 mb-3 rounded" placeholder="ค้นหาจากข้อมูลกิจกรรม"
                    value="{{ request('query') }}"></div>
            <div class="ms-2"><select id="model" name="model" class="form-control shadow-lg p-2 mb-3 rounded">
                    <option value="">-- เลือกประเภทข้อมูล --</option>
                    <option value="ครุภัณฑ์" {{ request('model') == 'ครุภัณฑ์' ? 'selected' : '' }}>
                        ครุภัณฑ์</option>
                    <option value="เอกสาร" {{ request('model') == 'เอกสาร' ? 'selected' : '' }}>
                        เอกสาร</option>
                    <option value="บุคลากร" {{ request('model') == 'บุคลากร' ? 'selected' : '' }}>
                        บุคลากร</option>
                </select></div>
            <div class="ms-2"><select id="menu" name="menu" class="form-control shadow-lg p-2 mb-3 rounded">
                    <option value="">-- เลือกประเภทกิจกรรม --</option>
                    <option value="เพิ่มข้อมูล" {{ request('menu') == 'เพิ่มข้อมูล' ? 'selected' : '' }}>
                        เพิ่มข้อมูล</option>
                    <option value="แก้ไขข้อมูล" {{ request('menu') == 'แก้ไขข้อมูล' ? 'selected' : '' }}>
                        แก้ไขข้อมูล</option>
                    <option value="ลบข้อมูลแบบซอฟต์" {{ request('menu') == 'ลบข้อมูลแบบซอฟต์' ? 'selected' : '' }}>
                        ลบข้อมูลแบบซอฟต์</option>
                    <option value="ลบข้อมูลถาวร" {{ request('menu') == 'ลบข้อมูลถาวร' ? 'selected' : '' }}>
                        ลบข้อมูลถาวร</option>
                    <option value="ส่งออกข้อมูล" {{ request('menu') == 'ส่งออกข้อมูล' ? 'selected' : '' }}>
                        ส่งออกข้อมูล</option>
                    <option value="เข้าสู่ระบบ" {{ request('menu') == 'เข้าสู่ระบบ' ? 'selected' : '' }}>
                        เข้าสู่ระบบ</option>
                    <option value="ออกจากระบบ" {{ request('menu') == 'ออกจากระบบ' ? 'selected' : '' }}>
                        ออกจากระบบ</option>
                </select></div>
            <div class="ms-2"><button type="submit" class="btn btn-primary shadow-lg p-2 mb-3 rounded">ค้นหา</button>
            </div>
            <div class="ms-2"> <button type="button" class="btn btn-danger shadow-lg p-2 mb-3 rounded"
                    onclick="window.location='{{ route('activity.search') }}'"
                    style="width: 100%">ล้างการค้นหา</button>
            </div>
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
                                'name' => 'ชื่อ',
                                'type' => 'ประเภท',
                                'description' => 'คำอธิบาย',
                                'unit' => 'หน่วยนับ',
                                'price' => 'ราคา',
                                'title' => 'หัวข้อ',
                                'user' => 'ผู้ดูแล',
                                'amount' => 'จำนวนทั้งหมด',
                                'number' => 'หมายเลขครุภัณฑ์',
                                'location' => 'ที่อยู่',
                                // 'status_found' => 'พบ',
                                // 'status_not_found' => 'ไม่พบ',
                                // 'status_broken' => 'ชำรุด',
                                // 'status_disposal' => 'จำหน่าย',
                                // 'status_transfer' => 'โอน',
                                'total_price' => 'ราคารวม',

                                // export
                                'unit_filter' => 'หน่วยนับ',
                                'title_filter' => 'หัวข้อ',
                                'user_filter' => 'ผู้ดูแล',
                                'location_filter' => 'ที่อยู่',
                                'query' => 'คำค้นหา',

                                //document
                                'original_name' => 'ชื่อเอกสาร',
                                'stored_name' => 'ชื่อเอกสารที่ใช้เก็บในระบบ',
                                'document_type' => 'ประเภทเอกสาร',
                                'date' => 'วันที่ดำเนินการ',

                                // user
                                'username' => 'ชื่อผู้ใช้',
                                'firstname' => 'ชื่อจริง',
                                'lastname' => 'นามสกุล',
                                'user_type' => 'ระดับผู้ใช้',
                                'prefix' => 'คำนำหน้าชื่อ',
                                'email' => 'อีเมล',
                            ];
                            if (!empty($log->properties)) {
                                if (
                                    $log->description == 'บุคลากร' &&
                                    !($log->menu == 'เข้าสู่ระบบ' || $log->menu == 'ออกจากระบบ')
                                ) {
                                    $ordered = $log->orderUserProperties();
                                } elseif ($log->description == 'เอกสาร') {
                                    $ordered = $log->orderDocumentProperties();
                                } elseif ($log->description == 'ครุภัณฑ์' && $log->menu == 'ส่งออกข้อมูล') {
                                    $ordered = $log->orderExportProperties();
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
