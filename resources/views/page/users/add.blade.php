<x-layouts.app>
    <h3 class="text-dark">เพิ่มบุคลากร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">คำนำหน้า</label>
                        <select name="prefix" class="form-control" required>
                            <option value="">-- เลือกคำนำหน้า --</option>
                            @foreach($prefixes as $prefix)
                                <option value="{{ $prefix->id }}">{{ $prefix->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อ</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">นามสกุล</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">สิทธิ์บุคลากร</label>
                    <select name="user_type" class="form-control" required>
                        <option value="">-- ระบุสิทธิ์บุคลากร --</option>
                        <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                        <option value="เจ้าหน้าที่สาขา">เจ้าหน้าที่สาขา</option>
                        <option value="ผู้ปฏิบัติงานบริหาร">ผู้ปฏิบัติงานบริหาร</option>
                        <option value="อาจารย์">อาจารย์</option>
                    </select>
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" required>
                </div> --}}
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('user') }}" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</x-layouts.app>
