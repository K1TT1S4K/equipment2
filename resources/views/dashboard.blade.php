<x-layouts.app>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <div class="container">
                <div class="d-flex" style="max-height: 500px">
                    <div class="left row d-flex justify-content-start">
                        <h2 class="text-center mb-0 pb-0">ครุภัณฑ์ที่ดำเนินการ</h2>
                        <canvas class="pt-0 mt-0" id="myChart2" style="max-width:800px; max-height:500px;"></canvas>
                    </div>
                    <div class="left row d-flex justify-content-center">
                        <h2 class="text-center mb-0 pb-0">ครุภัณฑ์ทั้งหมด</h2>
                        <canvas class="pt-0 mt-0" id="myChart" style="max-width:500px; max-height:500px;"></canvas>
                    </div>
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
                                @php
                                    $items = $equipments->where('user_id', auth()->user()->id);
                                @endphp
                                @can('admin-or-branch-or-officer')
                                    @php
                                        $items = $equipments;
                                    @endphp
                                @endcan
                                @foreach ($items as $item)
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
                                @php
                                    $items = $equipments->where('user_id', auth()->user()->id);
                                @endphp
                                @can('admin-or-branch-or-officer')
                                    @php
                                        $items = $equipments;
                                    @endphp
                                @endcan
                                <tr>
                                    @foreach ($items as $item)
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
{{-- <script src="{{ asset('js/Chart.js') }}" defer></script>
<script src="{{ asset('js/chartjs-plugin-datalabels.min.js') }}" defer></script> --}}
<script>
    function loadScript(src) {
        return new Promise((resolve, reject) => {
            // สร้าง object แท็ก script
            const script = document.createElement('script');
            // กำหนดลิงค์ให้ object
            script.src = src;
            // รัน resolve เมื่อ object script โหลดเสร็จ
            script.onload = () => resolve();
            // รัน reject เมื่อ object script โหลดไม่เสร็จ
            script.onerror = () => reject();
            // นำ script ใส่เข้าไปในแท็ก head
            document.head.appendChild(script);
        });
    }

    (async function() {
        await loadScript('{{ asset('js/Chart.js') }}');
        await loadScript('{{ asset('js/chartjs-plugin-datalabels.min.js') }}');
        console.log('ทั้งสอง script โหลดเรียบร้อย ✅');

        (function() {
            var xValues = ["พบ", "ไม่พบ", "ชำรุด", "จำหน่าย", "โอน"];
            var yValues = [{{ $totals['total_found'] }}, {{ $totals['total_not_found'] }},
                {{ $totals['total_broken'] }},
                {{ $totals['total_disposal'] }}, {{ $totals['total_transfer'] }}
            ];
            var barColors = [
                "#28a745",
                "#dc3545",
                "#ffc107",
                "#6c757d",
                "#17a2b8"
            ];
            console.log(document.getElementById('myChart'));
            console.log(new Chart("myChart3"));
            new Chart("myChart", {
                type: "pie",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true, // ทำให้สัดส่วนไม่เพี้ยน
                    aspectRatio: 1, // 1:1 สำหรับ pie/donut chart
                    layout: {
                        padding: 0 // กำหนด padding รอบกราฟเป็น 0
                    },
                    legend: {
                        display: true,
                        position: "right",
                        labels: {
                            fontSize: 20, // << ขนาดตัวอักษร
                            boxWidth: 30, // << ขนาดสี่เหลี่ยมสีด้านหน้า
                            padding: 30
                        }
                    },
                    title: {
                        display: false
                    },
                    plugins: {
                        datalabels: {
                            color: "#fff", // สีตัวอักษรบนกราฟ
                            font: {
                                weight: "bold",
                                size: 14
                            },
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                let percentage = (value * 100 / sum).toFixed(1);

                                // 👇 ถ้าเปอร์เซ็นต์น้อยกว่า 5% จะไม่แสดง
                                if (percentage < 5) {
                                    return null;
                                }
                                return percentage + "%";
                            }
                        }
                    }

                },
                plugins: [ChartDataLabels] // เปิดใช้งาน plugin
            });
        })();

        //bar chart
        (function() {
            // ดึงปีปัจจุบันเป็น ค.ศ.
            let currentYearAD = new Date().getFullYear();
            // แปลงเป็น พ.ศ.
            let currentYearBE = currentYearAD + 543;

            const xValues = [currentYearBE - 2, currentYearBE - 1, currentYearBE]; // 3 แท่งในแต่ละชุด

            // ข้อมูล 5 ชุด
            const dataset1 = [
                {{ $totalsByYear[$twoYearsAgo]->total_disposal_request ?? 0 }},
                {{ $totalsByYear[$lastYear]->total_disposal_request ?? 0 }},
                {{ $totalsByYear[$currentYear]->total_disposal_request ?? 0 }}
            ];
            const dataset2 = [
                {{ $totalsByYear[$twoYearsAgo]->total_not_found ?? 0 }},
                {{ $totalsByYear[$lastYear]->total_not_found ?? 0 }},
                {{ $totalsByYear[$currentYear]->total_not_found ?? 0 }}
            ];
            const dataset3 = [
                {{ $totalsByYear[$twoYearsAgo]->total_broken ?? 0 }},
                {{ $totalsByYear[$lastYear]->total_broken ?? 0 }},
                {{ $totalsByYear[$currentYear]->total_broken ?? 0 }}
            ];
            const dataset4 = [
                {{ $totalsByYear[$twoYearsAgo]->total_disposal ?? 0 }},
                {{ $totalsByYear[$lastYear]->total_disposal ?? 0 }},
                {{ $totalsByYear[$currentYear]->total_disposal ?? 0 }}
            ];
            const dataset5 = [
                {{ $totalsByYear[$twoYearsAgo]->total_transfer ?? 0 }},
                {{ $totalsByYear[$lastYear]->total_transfer ?? 0 }},
                {{ $totalsByYear[$currentYear]->total_transfer ?? 0 }}
            ];

            // สีของแต่ละชุด
            const colors = [
                "#28a745",
                "#dc3545",
                "#ffc107",
                "#6c757d",
                "#17a2b8"
            ];

            const ctx = document.getElementById("myChart2").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                            label: "ยื่นแทงจำหน่าย",
                            backgroundColor: colors[0],
                            data: dataset1
                        },
                        {
                            label: "ไม่พบ",
                            backgroundColor: colors[1],
                            data: dataset2
                        },
                        {
                            label: "ชำรุด",
                            backgroundColor: colors[2],
                            data: dataset3
                        },
                        {
                            label: "แทงจำหน่าย",
                            backgroundColor: colors[3],
                            data: dataset4
                        },
                        {
                            label: "โอน",
                            backgroundColor: colors[4],
                            data: dataset5
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: "Bar Chart 5 Sets x 3 Bars"
                        },
                        datalabels: {
                            color: '#fff'
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        })();
    })();
</script>
