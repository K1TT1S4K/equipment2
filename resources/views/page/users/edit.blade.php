<x-layouts.app>
    <h3 class="text-dark">แก้ไขข้อมูลบุคลากร</h3>
    <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                        <select name="prefix" class="form-control" required>
                            <option value="">-- เลือกคำนำหน้า --</option>
                            @foreach($prefixes as $prefix)
                                <option value="{{ $prefix->id }}" {{ $user->prefix_id == $prefix->id ? 'selected' : '' }}>
                                    {{ $prefix->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('prefix')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                        @error('firstname')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                        @error('lastname')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">สิทธิ์บุคลากร <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control" required>
                        <option value="">-- ระบุสิทธิ์บุคลากร --</option>
                        <option value="ผู้ดูแลระบบ" {{ $user->user_type == 'ผู้ดูแลระบบ' ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                        <option value="เจ้าหน้าที่สาขา" {{ $user->user_type == 'เจ้าหน้าที่สาขา' ? 'selected' : '' }}>เจ้าหน้าที่สาขา</option>
                        <option value="ผู้ปฏิบัติงานบริหาร" {{ $user->user_type == 'ผู้ปฏิบัติงานบริหาร' ? 'selected' : '' }}>ผู้ปฏิบัติงานบริหาร</option>
                        <option value="อาจารย์" {{ $user->user_type == 'อาจารย์' ? 'selected' : '' }}>อาจารย์</option>
                    </select>
                    @error('user_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน (หากไม่ต้องการเปลี่ยน ปล่อยว่างไว้)</label>
                    <input type="password" name="password" class="form-control">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">บันทึก</button>
                <a href="{{ route('user') }}" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</x-layouts.app>
