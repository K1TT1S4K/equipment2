<x-layouts.app>
    <h3 class="text-dark">เพิ่มบุคลากร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" required>
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                        <select name="prefix" class="form-control" required>
                            <option value="">-- เลือกคำนำหน้า --</option>
                            @foreach ($prefixes as $prefix)
                                <option value="{{ $prefix->id }}">{{ $prefix->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">สิทธิ์บุคลากร <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control" required>
                        <option value="">-- ระบุสิทธิ์บุคลากร --</option>
                        <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                        <option value="เจ้าหน้าที่พ้สดุ">เจ้าหน้าที่พ้สดุ</option>
                        <option value="ผู้ปฏิบัติงานบริหาร">ผู้ปฏิบัติงานบริหาร</option>
                        <option value="ผู้ดูแลครุภัณฑ์">ผู้ดูแลครุภัณฑ์</option>
                    </select>
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" required>
                </div> --}}
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="text-end"><button type="submit" class="btn btn-primary">บันทึก</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
            </form>
        </div>
    </div>
</x-layouts.app>
