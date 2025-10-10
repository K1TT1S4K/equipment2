<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบแทงจำหน่ายครุภัณฑ์</title>
    <link rel="icon" href="{{ asset('images/RMUTI-logo.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body style="background-color: var(--bs-light-green);">
    <x-layouts.app.navbar>
    </x-layouts.app.navbar>
    <main>
        <div class="container-fluid mt-5 pt-5">
            {{ $slot }}
        </div>
    </main>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
</body>
<script>
    // popup จัดการข้อมูลย่อยในหน้าแก้ไขครุภัณฑ์
    (function() {
        $(document).ready(function() {
            loadlocations();
            loadunits();
            loadtitles();
            loadtitlesforclone();

            // ที่อยู่
            function loadlocations() {
                let equipmentLocationId = $("#currentEquipmentLocationId").val();
                $.get("{{ route('locations.index') }}", function(data) {
                    $("#locationSelect").html("")

                    เพิ่ม option เริ่มต้น
                    $("#locationSelect").append(`<option value="">-- เลือกที่อยู่ --</option>`);

                    let rows = '';
                    data.forEach(loc => {
                        if (loc.is_locked == 0) {
                            rows += `
                        <tr data-id="${loc.id}">
                            <td class="location-name">${loc.name}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary editBtnlocation">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtnlocation">ลบ</button>
                            </td>
                        </tr>`;
                            $("#locationSelect").append(
                                `<option value="${loc.id}" ${(equipmentLocationId && equipmentLocationId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                            );
                        }
                    });
                    $('#locationTableBody').html(rows);
                });
            }

            // หน่วยนับ
            function loadunits() {
                let equipmentUnitId = $("#currentEquipmentUnitId").val();
                $.get("{{ route('equipment_units.index') }}", function(data) {
                    $("#unitSelect").html("")

                    เพิ่ม option เริ่มต้น
                    $("#unitSelect").append(`<option value="">-- เลือกหน่วยนับ --</option>`);

                    let rows = '';
                    data.forEach(loc => {
                        if (loc.is_locked == 0) {
                            rows += `
                        <tr data-id="${loc.id}">
                            <td class="unit-name">${loc.name}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary editBtnunit">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtnunit">ลบ</button>
                            </td>
                        </tr>`;
                            $("#unitSelect").append(
                                `<option value="${loc.id}" ${(equipmentUnitId && equipmentUnitId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                            );
                        }
                    });
                    $('#unitTableBody').html(rows);
                });
            }

            // หัวข้อ
            function loadtitles() {
                let equipmentTitleId = $("#currentEquipmentTitleId").val();
                $.get("{{ route('titles.index') }}", function(data) {
                    $("#titleSelect").html("")

                    เพิ่ม option เริ่มต้น
                    $("#titleSelect").append(`<option value="">-- เลือกหัวข้อ --</option>`);

                    let rows = '';
                    data.forEach(loc => {
                        if (loc.is_locked == 0) {
                            rows += `
                        <tr data-id="${loc.id}">
                            <td class="title-name">${loc.name}</td>
                                                        <td class="text-center">
                                <button class="btn btn-sm btn-primary editBtntitle">แก้ไข</button>
                                {{-- <button class="btn btn-sm btn-danger deleteBtntitle">ลบ</button> --}}
                            </td>
                        </tr>`;
                            $("#titleSelect").append(
                                `<option value="${loc.id}" ${(equipmentTitleId && equipmentTitleId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                            );
                        }
                    });
                    $('#titleTableBody').html(rows);
                });
            }

            // โคลนหัวข้อ
            function loadtitlesforclone() {
                $.get("{{ route('titles.index') }}", function(data) {
                    console.table(data);
                    let rows = '';
                    data.forEach(loc => {
                        rows += `
                        <tr data-id="${loc.id}">
                            <td class="title-name">${loc.name}</td>
                                                        <td class="text-center">
                                                           ${!loc.is_locked
            ? `<button class="btn btn-sm btn-primary editBtntitleforclone">แก้ไข</button>
               <a href="/titles/${loc.id}/clone" class="btn btn-sm btn-success">โคลน</a>`
            : '-'}
                            </td>
                        </tr>`;
                    });
                    $('#cloneTitleTableBody').html(rows);
                });
            }

            // เพิ่ม ที่อยู่
            $('#addlocationRow').click(function() {
                $('#locationTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newlocationInput" placeholder="กรอกชื่อที่อยู่"></td>
                    <td>
                        <button class="btn btn-success saveNewlocationBtn">ตกลง</button>
                        <button class="btn btn-secondary cancelNewlocationBtn">ยกเลิก</button>
                    </td>
                </tr>
            `);
            });

            // เพิ่ม หน่วยนับ
            $('#addunitRow').click(function() {
                $('#unitTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newunitInput" placeholder="กรอกชื่อที่อยู่"></td>
                    <td>
                        <button class="btn btn-success saveNewunitBtn">ตกลง</button>
                        <button class="btn btn-secondary cancelNewunitBtn">ยกเลิก</button>
                    </td>
                </tr>
            `);
            });

            // เพิ่ม หัวข้อ
            $('#addtitleRow').click(function() {
                $('#titleTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newtitleNameInput" placeholder="กรอกหัวข้อ"></td>
                    <td>
                        <button class="btn btn-success saveNewtitleBtn">ตกลง</button>
                        <button class="btn btn-secondary cancelNewtitleBtn">ยกเลิก</button>
                    </td>
                </tr>
            `);
            });

            // บันทึก ที่อยู่ใหม่
            $(document).on('click', '.saveNewlocationBtn', function() {
                const row = $(this).closest('tr');
                const name = row.find('.newlocationInput').val();

                $.post("{{ route('locations.store') }}", {
                    name: name,
                    _token: '{{ csrf_token() }}'
                }, function() {
                    loadlocations();
                });
            });

            // บันทึก หน่วยนับ
            $(document).on('click', '.saveNewunitBtn', function() {
                const row = $(this).closest('tr');
                const name = row.find('.newunitInput').val();

                $.post("{{ route('equipment_units.store') }}", {
                    name: name,
                    _token: '{{ csrf_token() }}'
                }, function() {
                    loadunits();
                });
            });

            // บันทึก หัวข้อ
            $(document).on('click', '.saveNewtitleBtn', function() {
                const row = $(this).closest('tr');
                const name = row.find('.newtitleNameInput').val();

                $.post("{{ route('titles.store') }}", {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    function() {
                        loadtitles();
                    });
            });

            // ยกเลิกแถวใหม่ ที่อยู่
            $(document).on('click', '.cancelNewlocationBtn', function() {
                $(this).closest('tr').remove();
            });

            // ยกเลิกแถวใหม่ ที่อยู่
            $(document).on('click', '.cancelNewunitBtn', function() {
                $(this).closest('tr').remove();
            });

            // ยกเลิกแถวใหม่ หัวข้อ
            $(document).on('click', '.cancelNewtitleBtn', function() {
                $(this).closest('tr').remove();
            });

            // แก้ไข ที่อยู่
            $(document).on('click', '.editBtnlocation', function() {
                const row = $(this).closest('tr');
                const name = row.find('.location-name').text();
                row.find('.location-name').html(
                    `<input type="text" class="form-control editInput" value="${name}">`);
                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtnlocation">ตกลง</button>`);
            });

            // แก้ไข หน่วยนับ
            $(document).on('click', '.editBtnunit', function() {
                const row = $(this).closest('tr');
                const name = row.find('.unit-name').text();
                row.find('.unit-name').html(
                    `<input type="text" class="form-control editInput" value="${name}">`);
                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtnunit">ตกลง</button>`);
            });

            // แก้ไข หัวข้อ
            $(document).on('click', '.editBtntitle', function() {
                const row = $(this).closest('tr');
                const name = row.find('.title-name').text();
                row.find('.title-name').html(
                    `<input type="text" class="form-control editNameInput" value="${name}">`);

                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtntitle">ตกลง</button>`);
            });

            // แก้ไข หัวข้อ
            $(document).on('click', '.editBtntitleforclone', function() {
                const row = $(this).closest('tr');
                const name = row.find('.title-name').text();
                row.find('.title-name').html(
                    `<input type="text" class="form-control editNameInput" value="${name}">`);

                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtntitleforclone">ตกลง</button>`);
            });

            // บันทึกการแก้ไข ที่อยู่
            $(document).on('click', '.saveEditBtnlocation', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editInput').val();

                $.ajax({
                    url: `/locations/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadlocations();
                    }
                });
            });

            // บันทึกการแก้ไข หน่วยนับ
            $(document).on('click', '.saveEditBtnunit', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editInput').val();

                $.ajax({
                    url: `/equipment_units/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadunits();
                    }
                });
            });

            // บันทึกการแก้ไข หัวข้อ
            $(document).on('click', '.saveEditBtntitle', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editNameInput').val();

                $.ajax({
                    url: `/titles/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadtitles();
                    }
                });
            });

            // บันทึกการแก้ไข หัวข้อจากปุ่มโคลน
            $(document).on('click', '.saveEditBtntitleforclone', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editNameInput').val();

                $.ajax({
                    url: `/titles/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadtitlesforclone();
                    }
                });
            });

            // ลบ ที่อยู่
            $(document).on('click', '.deleteBtnlocation', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                $.get(`/locations/${id}/check-usage`, function(res) {
                    if (res.in_use) {
                        alert('ไม่สามารถลบได้ เนื่องจากที่อยู่นี้ถูกใช้งานอยู่');
                    } else {
                        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบ?")) {
                            $.ajax({
                                url: `/locations/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    loadlocations();
                                }
                            });
                        }
                    }
                });
            });

            // ลบ หน่วยนับ
            $(document).on('click', '.deleteBtnunit', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                $.get(`/equipment_units/${id}/check-usage`, function(res) {
                    if (res.in_use) {
                        alert('ไม่สามารถลบได้ เนื่องจากที่อยู่นี้ถูกใช้งานอยู่');
                    } else {
                        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบ?")) {
                            $.ajax({
                                url: `/equipment_units/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    loadunits();
                                }
                            });
                        }
                    }
                });
            });

            // ลบ หัวข้อ
            $(document).on('click', '.deleteBtntitle', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                $.get(`/titles/${id}/check-usage`, function(res) {
                    if (res.in_use) {
                        alert('ไม่สามารถลบได้ เนื่องจากที่อยู่นี้ถูกใช้งานอยู่');
                    } else {
                        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบ?")) {
                            $.ajax({
                                url: `/titles/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    loadtitles();
                                }
                            });
                        }
                    }
                });
            });
        });
    })();
</script>

</html>
