<x-layouts.app>
    <h1 class="text-dark mt-1">โปรไฟล์ของฉัน</h1>

    <div class="card mt-2 w-90 p-4 mx-auto justify-content-center">
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
                        @foreach($prefixes as $prefix)
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
                <label class="form-label">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
                <input type="password" name="password" class="form-control border border-dark">
            </div>

            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
</x-layouts.app>
