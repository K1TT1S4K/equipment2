<x-layouts.app>
    <h3 class="text-dark">เพิ่มบุคลากร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้สำหรับล็อกอินเข้าสู่ระบบ เช่น Admin1234" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
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
                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control" placeholder="ชื่อจริง" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control" placeholder="นามสกุล" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">สิทธิ์บุคลากร <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control" required>
                        <option value="">-- ระบุสิทธิ์บุคลากร --</option>
                        <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                        <option value="เจ้าหน้าที่สาขา">เจ้าหน้าที่สาขา</option>
                        <option value="ผู้ปฏิบัติงานบริหาร">ผู้ปฏิบัติงานบริหาร</option>
                        <option value="อาจารย์">อาจารย์</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
                </div>
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('user') }}" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</x-layouts.app>
