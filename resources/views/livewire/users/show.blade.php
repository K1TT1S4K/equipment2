<x-layouts.app>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4>Users List</h4>
                <a href="{{ route('user.add')}}" class="btn btn-success">+ เพิ่มบุคลากร</a>
            </div>
            <table class="table table-striped table-hover w-full">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        {{-- <th class="border">
                            <input type="checkbox" class="form-check-input">
                        </th> --}}
                        <th class="border">#</th>
                        <th class="border">ชื่อผู้ใช้</th>
                        <th class="border">คำนำหน้า</th>
                        <th class="border">ชื่อ</th>
                        <th class="border">นามสกุล</th>
                        <th class="border">ระดับผู้ใช้</th>
                        <th class="border">อีเมล</th>
                        <th class="border">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="border">{{ $user->username }}</td>
                        <td class="border">{{ $user->prefix->name }}</td>
                        <td class="border">{{ $user->firstname }}</td>
                        <td class="border">{{ $user->lastname }}</td>
                        <td class="border text-center">{{ $user->user_type }}</td>
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

                        <td class="border">{{ $user->email }}</td>

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
        </div>
    </div>
</x-layouts.app>
