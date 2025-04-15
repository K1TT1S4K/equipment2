<x-layouts.app>
    <h3 class="text-dark mb-4">จัดการบัญชีผู้ใช้</h3>
    <form method="GET" action="{{ route('user.search') }}" class="mb-3">
        <div class="d-flex">
            <input type="text" name="search" class="form-control shadow-lg p-2 mb-3 rounded" placeholder="ค้นหาบัญชีผู้ใช้..." value="{{ request()->get('search') }}">
            <select name="user_type" class="form-control ms-2 shadow-lg p-2 mb-3 rounded">
                <option value="">-- เลือกระดับผู้ใช้ --</option>
                <option value="ผู้ดูแลระบบ" {{ request()->get('user_type') == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                <option value="เจ้าหน้าที่สาขา" {{ request()->get('user_type') == 'เจ้าหน้าที่สาขา' ? 'selected' : '' }}>เจ้าหน้าที่สาขา</option>
                <option value="ผู้ปฏิบัติงานบริหาร" {{ request()->get('user_type') == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร</option>
                <option value="อาจารย์" {{ request()->get('user_type') == 'อาจารย์' ? 'selected' : '' }}>อาจารย์</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2 shadow-lg p-2 mb-3 rounded">ค้นหา</button>
        </div>
    </form>
    <div class="card w-auto mx-auto shadow-lg mb-3 bg-body rounded">
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                {{-- <h4>รายการผู้ใช้งาน</h4> --}}
                <a href="{{ route('user.add')}}" class="btn btn-success mb-3">+ เพิ่มบุคลากร</a>
            </div>
            <table class="table table-striped table-hover w-full">
                <thead class="table-dark text-white text-center border border-dark">
                    <tr>
                        {{-- <th>
                            <input type="checkbox" class="form-check-input">
                        </th> --}}
                        <th>#</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>คำนำหน้า</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>ระดับผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                    <tr class="align-middle border border-secondary-subtle">
                        {{-- <td class="text-center border border-dark">
                            <input type="checkbox" class="form-check-input">
                        </td> --}}
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td >{{ $user->username }}</td>
                        <td >{{ $user->prefix->name }}</td>
                        <td >{{ $user->firstname }}</td>
                        <td >{{ $user->lastname }}</td>
                        <td class="text-center">{{ $user->user_type }}</td>
                        {{-- <td>
                            <form action="{{ route('user.update', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name="user_type" class="form-control" required>
                                    <option value="">-- ระบุสิทธิ์บุคลากร --</option>
                                    <option value="ผู้ดูแลระบบ" {{ $user->user_type == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                                    <option value="เจ้าหน้าที่สาขา" {{ $user->user_type == 'เจ้าหน้าที่สาขา' ? 'selected' : '' }}>เจ้าหน้าที่สาขา</option>
                                    <option value="ผู้ปฏิบัติงานบริหาร" {{ $user->user_type == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร</option>
                                    <option value="อาจารย์" {{ $user->user_type == 'อาจารย์' ? 'selected' : '' }}>อาจารย์</option>
                                </select>
                            </form>
                        </td> --}}

                        <td >{{ $user->email }}</td>

                        <td class="text-center">
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

            </table>
            <!-- แสดงผลการแบ่งหน้า -->
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
