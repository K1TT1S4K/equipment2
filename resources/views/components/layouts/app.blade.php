<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบแทงจำหน่ายครุภัณฑ์</title>
    <link rel="icon" href="{{ asset('storage/RMUTI.png') }}" type="image/png">

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
            loadtypes();
            loadunits();
            loadtitles();
            loadtitlesforclone();

            // ที่อยู่
            function loadlocations() {
                let equipmentLocationId = $("#currentEquipmentLocationId").val();
                $.get("{{ route('locations.index') }}", function(data) {
                    $("#locationSelect").html("")
                    let rows = '';
                    data.forEach(loc => {
                        rows += `
                        <tr data-id="${loc.id}">
                            <td class="location-name">${loc.name}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtnlocation">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtnlocation">ลบ</button>
                            </td>
                        </tr>`;
                        $("#locationSelect").append(
                            `<option value="${loc.id}" ${(equipmentLocationId && equipmentLocationId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                        )
                    });
                    $('#locationTableBody').html(rows);
                });
            }

            // หน่วยนับ
            function loadunits() {
                let equipmentUnitId = $("#currentEquipmentUnitId").val();
                $.get("{{ route('equipment_units.index') }}", function(data) {
                    $("#unitSelect").html("")
                    let rows = '';
                    data.forEach(loc => {
                        rows += `
                        <tr data-id="${loc.id}">
                            <td class="unit-name">${loc.name}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtnunit">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtnunit">ลบ</button>
                            </td>
                        </tr>`;
                        $("#unitSelect").append(
                            `<option value="${loc.id}" ${(equipmentUnitId && equipmentUnitId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                        )
                    });
                    $('#unitTableBody').html(rows);
                });
            }

            // ประเภท
            function loadtypes() {
                let equipmentTypeId = $("#currentEquipmentTypeId").val();
                $.get("{{ route('types.index') }}", function(data) {
                    $("#equipmentTypeSelect").html("");

                    // เพิ่ม option เริ่มต้น
                    $("#equipmentTypeSelect").append(`<option value="">-- เลือกประเภท --</option>`);
                    let rows = '';
                    data.forEach(loc => {
                        rows += `
                        <tr data-id="${loc.id}">
                            <td class="type-name">${loc.name}</td>
                            <td class="type-title">
                                ${loc.title ? `${loc.title.group} - ${loc.title.name}` : '-'}
                            </td>

                            <td class="type-equipment-unit">${loc.equipment_unit?.name ?? '-'}</td>
                            <td class="type-amount">${loc.amount  ?? '-'}</td>
                            <td class="type-price">${loc.price  ?? '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtntype">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtntype">ลบ</button>
                            </td>
                        </tr>`;
                        $("#equipmentTypeSelect").append(
                            `<option value="${loc.id}" ${(equipmentTypeId && equipmentTypeId == loc.id) ? 'selected' : ''}>${loc.name}</option>`
                        )
                    });
                    $('#typeTableBody').html(rows);
                });
            }

            // หัวข้อ
            function loadtitles() {
                let equipmentTitleId = $("#currentEquipmentTitleId").val();
                $.get("{{ route('titles.index') }}", function(data) {
                    $("#titleSelect").html("")

                    // เพิ่ม option เริ่มต้น
                    $("#titleSelect").append(`<option value="">-- เลือกหัวข้อ --</option>`);

                    let rows = '';
                    data.forEach(loc => {
                        rows += `
                        <tr data-id="${loc.id}">
                            <td class="title-group">${loc.group}</td>
                            <td class="title-name">${loc.name}</td>
                                                        <td>
                                <button class="btn btn-sm btn-primary editBtntitle">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtntitle">ลบ</button>
                            </td>
                        </tr>`;
                        $("#titleSelect").append(
                            `<option value="${loc.id}" ${(equipmentTitleId && equipmentTitleId == loc.id) ? 'selected' : ''}>${loc.group} - ${loc.name}</option>`
                        )
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
                            <td class="title-group">${loc.group}</td>
                            <td class="title-name">${loc.name}</td>
                                                        <td class="text-center">
                                                                                            // <button class="btn btn-sm btn-primary">โคลน</button>
            <a href="/titles/${loc.id}/clone" class="btn btn-primary">Clone</a>


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

            // เพิ่ม ประเภท
            $('#addtypeRow').click(function() {
                $('#typeTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newtypeNameInput" placeholder="กรอกชื่อประเภท"></td>
                     <td><select name="newtypeTitleInput" id="newtypeTitleInput" class="form-control newtypeTitleInput">
                       <option value="">-</option>
                        @foreach ($titles as $t)
                        <option value="{{ $t->id }}">{{ $t->group }} - {{ $t->name }}</option>
                        @endforeach
                        </select></td>
                    <td><select name="newtypeUnitInput" id="newtypeUnitInput" class="form-control newtypeUnitInput">
                        <option value="">-</option>
                        @foreach ($units as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                        </select></td>
                    <td><input type="number" class="form-control newtypeAmountInput" placeholder="กรอกจำนวน"></td>
                    <td><input type="number" class="form-control newtypePriceInput" placeholder="กรอกราคา"></td>
                    <td>
                        <button class="btn btn-success saveNewtypeBtn">ตกลง</button>
                        <button class="btn btn-secondary cancelNewtypeBtn">ยกเลิก</button>
                    </td>
                </tr>
            `);
            });

            // เพิ่ม หัวข้อ
            $('#addtitleRow').click(function() {
                $('#titleTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newtitleGroupInput" placeholder="กรอกกลุ่ม"></td>
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

            // บันทึก ประเภทใหม่
            $(document).on('click', '.saveNewtypeBtn', function() {
                const row = $(this).closest('tr');
                const name = row.find('.newtypeNameInput').val();
                const title_id = row.find('.newtypeTitleInput').val();
                const equipment_unit_id = row.find('.newtypeUnitInput').val();
                const amount = row.find('.newtypeAmountInput').val();
                const price = row.find('.newtypePriceInput').val();

                $.post("{{ route('types.store') }}", {
                    name: name,
                    title_id: title_id,
                    equipment_unit_id: equipment_unit_id,
                    amount: amount,
                    price: price,
                    _token: '{{ csrf_token() }}'
                }, function() {
                    loadtypes();
                });
            });

            // บันทึก หัวข้อ
            $(document).on('click', '.saveNewtitleBtn', function() {
                const row = $(this).closest('tr');
                const name = row.find('.newtitleNameInput').val();
                const group = row.find('.newtitleGroupInput').val();

                $.post("{{ route('titles.store') }}", {
                        name: name,
                        group: group,
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

            // ยกเลิกแถวใหม่ ประเภท
            $(document).on('click', '.cancelNewtypeBtn', function() {
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

            // แก้ไข ประภท
            $(document).on('click', '.editBtntype', function() {
                const row = $(this).closest('tr');
                const name = row.find('.type-name').text();
                const title_id = row.find('.type-title').text();
                const equipment_unit_id = row.find('.type-equipment-unit').text();
                const amount = parseInt(row.find('.type-amount').text());
                const price = parseFloat(row.find('.type-price').text());

                row.find('.type-name').html(
                    `<input type="text" class="form-control editNameInput" value="${name}">`);
                row.find('.type-title').html(
                    // `<input type="text" class="form-control editUnitInput" value="${equipment_unit_id}">`


                    `<select name="editTitleInput" id="editTitleInput" class="form-control editTitleInput">
                        @foreach ($titles as $t)
                        <option value="{{ $t->id }}" >{{ $t->group }} - {{ $t->name }}</option>
                        @endforeach
                        </select>`
                );
                setTimeout(() => {
                    const select = row.find('.editTitleInput');
                    const targetName = title_id; // สมมุติว่าเป็นชื่อ เช่น "กิโลกรัม"

                    // หา option ที่มีข้อความตรงกับชื่อ
                    const matchingOption = select.find('option').filter(function() {
                        return $(this).text().trim() === targetName;
                    });

                    if (matchingOption.length) {
                        const matchedId = matchingOption.val(); // ดึง id ที่ตรงกับชื่อ
                        select.val(matchedId);
                    }
                }, 10);
                row.find('.type-equipment-unit').html(
                    // `<input type="text" class="form-control editUnitInput" value="${equipment_unit_id}">`


                    `<select name="editUnitInput" id="editUnitInput" class="form-control editUnitInput">
                        @foreach ($units as $u)
                        <option value="{{ $u->id }}" >{{ $u->name }}</option>
                        @endforeach
                        </select>`

                    // document.getElementById('editUnitInput').value = '5';

                );
                setTimeout(() => {
                    const select = row.find('.editUnitInput');
                    const targetName =
                        equipment_unit_id; // สมมุติว่าเป็นชื่อ เช่น "กิโลกรัม"

                    // หา option ที่มีข้อความตรงกับชื่อ
                    const matchingOption = select.find('option').filter(function() {
                        return $(this).text().trim() === targetName;
                    });

                    if (matchingOption.length) {
                        const matchedId = matchingOption.val(); // ดึง id ที่ตรงกับชื่อ
                        select.val(matchedId);
                    }
                }, 10);

                console.log('111');
                console.log(row);
                console.log(row.find('.editUnitInput').val());
                row.find('.type-amount').html(
                    `<input type="number" class="form-control editAmountInput" value="${amount}">`
                );
                row.find('.type-price').html(
                    `<input type="number" class="form-control editPriceInput" value="${price}">`
                );
                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtntype">ตกลง</button>`);
            });

            // แก้ไข หัวข้อ
            $(document).on('click', '.editBtntitle', function() {
                const row = $(this).closest('tr');
                const name = row.find('.title-name').text();
                const group = row.find('.title-group').text();
                row.find('.title-name').html(
                    `<input type="text" class="form-control editNameInput" value="${name}">`);

                row.find('.title-group').html(
                    `<input type="text" class="form-control editGroupInput" value="${group}">`);
                $(this).replaceWith(
                    `<button class="btn btn-success saveEditBtntitle">ตกลง</button>`);
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

            // บันทึกการแก้ไข ที่อยู่
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

            // บันทึกการแก้ไข ประเภท
            $(document).on('click', '.saveEditBtntype', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editNameInput').val();
                const title_id = row.find('.editTitleInput').val();
                const equipment_unit_id = row.find('.editUnitInput').val();
                // const equipment_unit_id = 1;
                // const amount = 900;
                // const price = 20;
                const amount = row.find('.editAmountInput').val();
                const price = row.find('.editPriceInput').val();
                console.log({
                    name,
                    title_id,
                    equipment_unit_id,
                    amount,
                    price
                });

                $.ajax({
                    url: `/types/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        title_id: title_id,
                        equipment_unit_id: equipment_unit_id,
                        amount: amount,
                        price: price,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        console.log("บันทึกสำเร็จ");
                        loadtypes();
                    }
                });
            });

            // บันทึกการแก้ไข หัวข้อ
            $(document).on('click', '.saveEditBtntitle', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.find('.editNameInput').val();
                const group = row.find('.editGroupInput').val();

                $.ajax({
                    url: `/titles/${id}`,
                    method: 'PUT',
                    data: {
                        name: name,
                        group: group,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadtitles();
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

            // ลบ ประเภท
            $(document).on('click', '.deleteBtntype', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');

                $.get(`/types/${id}/check-usage`, function(res) {
                    if (res.in_use) {
                        alert('ไม่สามารถลบได้ เนื่องจากที่อยู่นี้ถูกใช้งานอยู่');
                    } else {
                        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบ?")) {
                            $.ajax({
                                url: `/types/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    loadtypes();
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
