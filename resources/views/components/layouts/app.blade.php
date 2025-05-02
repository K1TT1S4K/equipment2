<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>

<body style="background-color: var(--bs-light-green);">
    <x-layouts.app.navbar>
    </x-layouts.app.navbar>
    <main>
        <div class="container-fluid mt-5 pt-5">
            {{ $slot }}
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- สคริปที่ใช้ --}}
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        if (!sessionStorage.getItem('equipmentIndexVisited')) {
            sessionStorage.setItem('equipmentIndexVisited', 'true');

            document.getElementById('title_filter')?.form.submit();
            document.getElementById('unit_filter')?.form.submit();
            document.getElementById('location_filter')?.form.submit();
            document.getElementById('user_filter')?.form.submit();
            document.getElementById('title_id')?.form.submit();
            // goToBinMode();
        }
    });
</script>

{{-- ไปที่ถังขยะ --}}
<script>
    function goToBinMode() {
        const urlParams = new URLSearchParams(window.location.search);
        const currentBinMode = urlParams.get('bin_mode');

        if (currentBinMode == 1) {
            // ถ้าอยู่ในโหมดถังขยะแล้วให้กลับเป็นปกติ
            urlParams.delete('bin_mode');
        } else {
            // ถ้าไม่อยู่ในโหมดถังขยะให้เปิดโหมดถังขยะ
            urlParams.set('bin_mode', 1);
        }

        // เปลี่ยน URL และรีเฟรชหน้า
        window.location.search = urlParams.toString();
    }
</script>

{{-- เช็คบ็อกเลือกข้อมูล --}}
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.checkbox-item').forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('moveToTrashBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.checkbox-item:checked'))
            .map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('กรุณาเลือกรายการที่ต้องการย้ายไปถังขยะ');
            return;
        }

        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการย้ายรายการที่เลือกไปยังถังขยะ?')) {
            return;
        }

        fetch("{{ route('equipment.moveToTrash') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                window.location.reload(); // รีโหลดหน้าเพื่อดูผลลัพธ์
            })
            .catch(err => {
                console.error(err);
                alert('เกิดข้อผิดพลาดในการย้ายข้อมูลไปถังขยะ');
            });
    });

    const restoreBtn = document.getElementById('restoreFromTrashBtn');
    if (restoreBtn) {
        restoreBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.checkbox-item:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('กรุณาเลือกรายการที่ต้องการย้ายออกจากถังขยะ');
                return;
            }

            if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการย้ายรายการที่เลือกออกจากถังขยะ?')) {
                return;
            }

            fetch("{{ route('equipment.restoreFromTrash') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    window.location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('เกิดข้อผิดพลาดในการย้ายข้อมูลออกจากถังขยะ');
                });
        });
    }
</script>


{{-- ค้นหา --}}
<script>
    function searchTable() {
        const input = document.getElementById("equipments-search").value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
</script>


<script>
    // console.log('Script Loaded');
    document.addEventListener('DOMContentLoaded', function() {
        const titleSelect = document.getElementById('titleSelect');
        const typeSelect = document.getElementById('equipmentTypeSelect');

        titleSelect.addEventListener('change', function() {
            const titleId = this.value;

            // ล้าง options เดิม
            typeSelect.innerHTML = '<option value="">-- เลือกประเภท --</option>';

            if (titleId) {
                fetch(`/get-equipment-types/${titleId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(type) {
                            const option = document.createElement('option');
                            option.value = type.id;
                            option.text = type.name;
                            typeSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลประเภท:', error));
            }
        });
    });
</script>


{{-- popup จัดการ --}}
<script>
    $(document).ready(function() {
        loadlocations();
        loadtypes();
        // loadunits();
        // loadtitles();

        // ที่อยู่
        function loadlocations() {
            $.get("{{ route('locations.index') }}", function(data) {
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
                });
                $('#locationTableBody').html(rows);
            });
        }

        // ประเภท
        function loadtypes() {
            $.get("{{ route('types.index') }}", function(data) {
                let rows = '';
                data.forEach(loc => {
                    rows += `
                        <tr data-id="${loc.id}">
                            <td class="type-name">${loc.name}</td>
                            <td class="type-equipment-unit">${loc.equipment_unit?.name ?? '-'}</td>
                            <td class="type-amount">${loc.amount  ?? '-'}</td>
                            <td class="type-price">${loc.price  ?? '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtntype">แก้ไข</button>
                                <button class="btn btn-sm btn-danger deleteBtntype">ลบ</button>
                            </td>
                        </tr>`;
                });
                $('#typeTableBody').html(rows);
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

        // เพิ่ม ประเภท
        $('#addtypeRow').click(function() {
            $('#typeTableBody').prepend(`
                <tr>
                    <td><input type="text" class="form-control newtypeNameInput" placeholder="กรอกชื่อประเภท"></td>
                    <td><input type="number" class="form-control newtypeUnitInput" placeholder="กรอกหน่วยนับ"></td>
                    <td><input type="number" class="form-control newtypeAmountInput" placeholder="กรอกจำนวน"></td>
                    <td><input type="number" class="form-control newtypePriceInput" placeholder="กรอกราคา"></td>
                    <td>
                        <button class="btn btn-success saveNewtypeBtn">ตกลง</button>
                        <button class="btn btn-secondary cancelNewtypeBtn">ยกเลิก</button>
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

        // บันทึก ประเภทใหม่
        $(document).on('click', '.saveNewtypeBtn', function() {
            const row = $(this).closest('tr');
            const name = row.find('.newtypeNameInput').val();
            const equipment_unit_id = row.find('.newtypeUnitInput').val();
            const amount = row.find('.newtypeAmountInput').val();
            const price = row.find('.newtypePriceInput').val();

            $.post("{{ route('types.store') }}", {
                name: name,
                equipment_unit_id: equipment_unit_id,
                amount: amount,
                price: price,
                _token: '{{ csrf_token() }}'
            }, function() {
                loadtypes();
            });
        });

        // ยกเลิกแถวใหม่ ที่อยู่
        $(document).on('click', '.cancelNewlocationBtn', function() {
            $(this).closest('tr').remove();
        });

        // ยกเลิกแถวใหม่ ประเภท
        $(document).on('click', '.cancelNewtypeBtn', function() {
            $(this).closest('tr').remove();
        });

        // แก้ไข ที่อยู่
        $(document).on('click', '.editBtnlocation', function() {
            const row = $(this).closest('tr');
            const name = row.find('.location-name').text();
            row.find('.location-name').html(
                `<input type="text" class="form-control editInput" value="${name}">`);
            $(this).replaceWith(`<button class="btn btn-success saveEditBtnlocation">ตกลง</button>`);
        });

        // แก้ไข ประภท
        $(document).on('click', '.editBtntype', function() {
            const row = $(this).closest('tr');
            const name = row.find('.type-name').text();
            const equipment_unit_id = parseInt(row.find('.type-equipment-unit-id').text());
            const amount = parseInt(row.find('.type-amount').text());
            const price = parseFloat(row.find('.type-price').text());

            row.find('.type-name').html(
                `<input type="text" class="form-control editNameInput" value="${name}">`);
            row.find('.type-equipment-unit-id').html(
                `<input type="number" class="form-control editUnitInput" value="${equipment_unit_id}">`
            );
            row.find('.type-amount').html(
                `<input type="number" class="form-control editAmountInput" value="${amount}">`);
            row.find('.type-price').html(
                `<input type="number" class="form-control editPriceInput" value="${price}">`);
            $(this).replaceWith(`<button class="btn btn-success saveEditBtntype">ตกลง</button>`);
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

        // บันทึกการแก้ไข ประเภท
        $(document).on('click', '.saveEditBtntype', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            const name = row.find('.editNameInput').val();
            const equipment_unit_id = row.find('.editUnitInput').val();
            const amount = row.find('.editAmountInput').val();
            const price = row.find('.editPriceInput').val();

            $.ajax({
                url: `/types/${id}`,
                method: 'PUT',
                data: {
                    name: name,
                    equipment_unit_id: equipment_unit_id,
                    amount: amount,
                    price: price,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    loadtypes();
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
    });
</script>

=======
>>>>>>> 7f26ebecd6daa066914ad1a1cc37f067cca1af98
</html>
