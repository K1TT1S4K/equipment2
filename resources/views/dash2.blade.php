<x-layouts.app>
    <style>
        tr[onclick] {
            cursor: pointer;
        }
    </style>

    @php
        $totals = DB::table('equipment')
            ->selectRaw(
                '
            SUM(status_found) as total_found,
            SUM(status_not_found) as total_not_found,
            SUM(status_broken) as total_broken,
            SUM(status_disposal) as total_disposal,
            SUM(status_transfer) as total_transfer
        ',
            )
            ->first();

        $equipments = DB::table('equipment')->get();
    @endphp

    <div class="container">
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('equipment.index', ['status' => 'not_found']) }}" class="text-decoration-none">
                    <div class="bg-danger p-3 text-center text-white rounded shadow-sm h-100">
                        <h4>ไม่พบ</h4>
                        <h3>{{ $totals->total_not_found }}</h3>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('equipment.index', ['status' => 'broken']) }}" class="text-decoration-none">
                    <div class="bg-warning p-3 text-center text-white rounded shadow-sm h-100">
                        <h4>ชำรุด</h4>
                        <h3>{{ $totals->total_broken }}</h3>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('equipment.index', ['status' => 'disposal']) }}" class="text-decoration-none">
                    <div class="bg-success p-3 text-center text-white rounded shadow-sm h-100">
                        <h4>จำหน่าย</h4>
                        <h3>{{ $totals->total_disposal }}</h3>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('equipment.index', ['status' => 'transfer']) }}" class="text-decoration-none">
                    <div class="bg-primary p-3 text-center text-white rounded shadow-sm h-100">
                        <h4>โอน</h4>
                        <h3>{{ $totals->total_transfer }}</h3>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-3"> <!-- ใช้ g-3 เพื่อให้เว้นช่องระหว่าง card -->
            <!-- ตารางที่ 1 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-white text-center border-secondary">
                                <tr>
                                    <th colspan="3" class="text-center text-white bg-danger">ไม่พบ</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">รหัส</th>
                                    <th style="width: 55%;">ชื่อ</th>
                                    <th style="width: 20%;">ไม่พบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipments as $item)
                                    @continue($item->status_not_found < 1)
                                    <tr onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                        <td class="text-center">{{ $item->number }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->status_not_found }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ตารางที่ 2 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-white text-center border-secondary">
                                <tr>
                                    <th colspan="3" class="text-center text-white bg-warning">ชำรุด</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">รหัส</th>
                                    <th style="width: 55%;">ชื่อ</th>
                                    <th style="width: 20%;">ชำรุด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipments as $item)
                                    @continue($item->status_broken < 1)
                                    <tr onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                        <td class="text-center">{{ $item->number }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->status_broken }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
