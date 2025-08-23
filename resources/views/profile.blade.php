<x-layouts.app>
    <h3 class="text-dark">โปรไฟล์ของฉัน</h3>

    <div class="card border border-dark shadow-lg p-5 mb-3 rounded">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" name="username" class="form-control border border-dark"
                        value="{{ $user->username }}" required>
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">คำนำหน้า</label>
                    <select name="prefix" class="form-control border border-dark" required>
                        @foreach ($prefixes as $prefix)
                            <option value="{{ $prefix->id }}">{{ $prefix->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">ชื่อ</label>
                    <input type="text" name="firstname" class="form-control border border-dark"
                        value="{{ $user->firstname }}" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">นามสกุล</label>
                    <input type="text" name="lastname" class="form-control border border-dark"
                        value="{{ $user->lastname }}" required>
                </div>
            </div>
            {{-- <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control border border-dark" value="{{ $user->email }}"
                    required>
            </div> --}}
            <div class="mb-3">
                <label class="form-label">รหัสผ่านเก่า</label>
                <input type="password" name="old_password" class="form-control border border-dark">
            </div>

            @error('old_password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="password" class="form-control border border-dark">
            </div>

            <div class="mb-3">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control border border-dark">
            </div>
            @error('password')
                <div class="alert alert-danger">รหัสผ่านใหม่ไม่ตรงกัน</div>
            @enderror

            <div class="text-end"><button type="submit" class="btn btn-primary">บันทึก</button></div>
        </form>
    </div>

    {{-- <div class="card w-auto mx-auto shadow-lg p-3 mb-5 bg-body rounded border border-dark">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">ชื่อ</label>
                        <input type="text" name="firstname" class="form-control" value="{{ $user->firstname }}" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">นามสกุล</label>
                        <input type="text" name="lastname" class="form-control" value="{{ $user->lastname }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">บันทึก</button>
            </form>
        </div>
    </div> --}}
</x-layouts.app>
