<x-layouts.app>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <div class="container">
                <div class="d-flex mb-2" style="max-height: 500px">
                    <div class="left row mb-0 pb-0 d-flex justify-content-start align-items-center">
                        <canvas id="myChart2" style="max-width:800px; max-height:500px;"></canvas>
                    </div>
                    <div class="left row mb-0 pb-0 d-flex justify-content-center">
                        <canvas id="myChart" style="max-width:500px; max-height:500px;"></canvas>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/0.7.0/chartjs-plugin-datalabels.min.js">
        </script>
        <script>
            // sessionStorage.setItem('dashboardRefreshed', 'false');
            // console.log(sessionStorage.getItem('dashboardRefreshed'))
            if (sessionStorage.getItem('dashboardRefreshed') == 'false') {
                sessionStorage.setItem('dashboardRefreshed', 'true');
                location.reload();
            }

            // pie chart
            (function() {
                var xValues = ["พบ", "ไม่พบ", "ชำรุด", "จำหน่าย", "โอน"];
                var yValues = [{{ $totals->total_found ?? 0 }}, {{ $totals->total_not_found ?? 0 }},
                    {{ $totals->total_broken ?? 0 }},
                    {{ $totals->total_disposal ?? 0 }}, {{ $totals->total_transfer ?? 0 }}
                ];
                var barColors = [
                    "#28a745",
                    "#dc3545",
                    "#ffc107",
                    "#6c757d",
                    "#17a2b8"
                ];

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

            (function() {
                // Chart ที่สอง
                const xValues2 = ["Italy", "France", "Spain", "USA", "Argentina"];
                const yValues2 = [55, 49, 44, 24, 15];
                const barColors2 = ["red", "green", "blue", "orange", "brown"];

                const ctx2 = document.getElementById("myChart2").getContext("2d");
                new Chart(ctx2, {
                    type: "bar",
                    data: {
                        labels: xValues2,
                        datasets: [{
                            backgroundColor: barColors2,
                            data: yValues2
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: "World Wine Production 2018"
                            }
                        }
                    }
                });
            })();
        </script>
    </div>
</x-layouts.app>
