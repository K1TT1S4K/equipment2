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

    <div class="container">
        <div class="row mb-3">
            {{-- <div class="col-sm bg-success ps-5 pe-5 pt-1 pb-1 m-1 text-center text-white rounded"
                href="{{ route('equipment.index') }}?title_filter=1&unit_filter=all&location_filter=all&user_filter=all">
                <h3>พบ</h3>
                <h3 class="text-white">{{ $totals->total_found }}</h3>
            </div> --}}
            <div class="col-sm bg-danger ps-5 pe-5 pt-1 pb-1 m-1 text-center text-white rounded">
                <h3>ไม่พบ</h3>
                <h3>{{ $totals->total_not_found }}</h3>
            </div>
            <div class="col-sm bg-warning ps-5 pe-5 pt-1 pb-1 m-1 text-center text-white rounded">
                <h3>ชำรุด</h3>
                <h3>{{ $totals->total_broken }}</h3>
            </div>
            <div class="col-sm  bg-success ps-5 pe-5 pt-1 pb-1 m-1 text-center text-white rounded">
                <h3>จำหน่าย</h3>
                <h3>{{ $totals->total_disposal }}</h3>
            </div>
            <div class="col-sm bg-primary ps-5 pe-5 pt-1 pb-1 m-1 text-center text-white rounded">
                <h3>โอน</h3>
                <h3>{{ $totals->total_transfer }}</h3>
            </div>
        </div>

        <div class="row w-90 justify-content-center mx-auto m-1">

            <!-- ตารางที่ 1 -->
            <div class="col-6">
                <div class="col-md-5">
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
            </div>



            <!-- ตารางที่ 2 -->
            <div class="col-md-6">
                <table class="mint-table w-100">
                    <thead class="table-dark text-white text-center border-secondary">
                        <tr>
                            <th colspan="3" class="text-center text-white bg-warning">ชำรุด</th>
                        </tr>
                        <tr>
                            <th style="width: 25%;">รหัส</th>
                            <th style="width: 59%;">ชื่อ</th>
                            <th style="width: 16%;">ชำรุด</th>
                        </tr>
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
