<x-layouts.app>
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
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <div class="container">
                <div class="chart-container mb-2" style="max-height: 500px">
                    <div class="left row mb-0 pb-0 d-flex justify-content-center" style="max-height:500px">
                        <canvas id="myChart" style="max-width:500px; max-height:500px;"></canvas>
                    </div>
                    {{-- <div class="right row mb-3">
                        <div class="bg-success pt-1 pb-1 text-center text-white rounded"
                            href="{{ route('equipment.index') }}?title_filter=1&unit_filter=all&location_filter=all&user_filter=all">
                            <h3>พบ
                            {{ $totals->total_found }}
                            ชิ้น</h3>
                        </div>
                        <div class="bg-danger pt-1 pb-1 text-center text-white rounded">
                            <h3>ไม่พบ</h3>
                            <h3>{{ $totals->total_not_found }}</h3>
                            <h3>ชิ้น</h3>
                        </div>
                        <div class="bg-warning pt-1 pb-1 text-center text-white rounded">
                            <h3>ชำรุด</h3>
                            <h3>{{ $totals->total_broken }}</h3>
                            <h3>ชิ้น</h3>
                        </div>
                        <div class="bg-secondary pt-1 pb-1 text-center text-white rounded">
                            <h3>จำหน่าย</h3>
                            <h3>{{ $totals->total_disposal }}</h3>
                            <h3>ชิ้น</h3>
                        </div>
                        <div class="bg-info pt-1 pb-1 text-center text-white rounded">
                            <h3>โอน</h3>
                            <h3>{{ $totals->total_transfer }}</h3>
                            <h3>ชิ้น</h3>
                        </div>
                    </div> --}}
                </div>
                {{-- <div id="pie-chart"></div> --}}
                <div class="row">
                    <!-- ตารางที่ 1 -->
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-white text-center border-secondary">
                                <tr>
                                    <th colspan="3" class="text-center text-white bg-danger">ไม่พบ</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">รหัส</th>
                                    <th style="width: 59%;">ชื่อ</th>
                                    <th style="width: 16%;">ไม่พบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipments as $item)
                                    @php
                                        if ($item->status_not_found < 1) {
                                            continue;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center"
                                            onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                            {{ $item->number }}</td>
                                        <td onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                            {{ $item->name }}</td>
                                        <td class="text-center"
                                            onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                            {{ $item->status_not_found }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ตารางที่ 2 -->
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-white text-center border-secondary">
                                <tr>
                                    <th colspan="3" class="text-center text-white bg-warning">ชำรุด</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">รหัส</th>
                                    <th style="width: 60%;">ชื่อ</th>
                                    <th style="width: 15%;">ชำรุด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($equipments as $item)
                                        @php
                                            if ($item->status_broken < 1) {
                                                continue;
                                            }
                                        @endphp
                                <tr>
                                    <td class="text-center"
                                        onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                        {{ $item->number }}</td>
                                    <td onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                        {{ $item->name }}</td>
                                    <td class="text-center"
                                        onclick="window.location='{{ route('equipment.edit', $item->id) }}'">
                                        {{ $item->status_broken }}</td>
                                </tr>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
