<x-layouts.app>
    <div class="py-4 w-75 mx-auto">
        <h1 class="text-dark mb-4">โปรไฟล์ของฉัน</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm p-4">
            <form action="{{ route('profile.update') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label fw-semibold">ชื่อผู้ใช้</label>
                        <input type="text" id="username" name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prefix" class="form-label fw-semibold">คำนำหน้า</label>
                        <select id="prefix" name="prefix"
                            class="form-select @error('prefix') is-invalid @enderror" required>
                            @foreach($prefixes as $prefix)
                                <option value="{{ $prefix->id }}" {{ $prefix->id == old('prefix', $user->prefix) ? 'selected' : '' }}>
                                    {{ $prefix->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="firstname" class="form-label fw-semibold">ชื่อ</label>
                        <input type="text" id="firstname" name="firstname"
                            class="form-control @error('firstname') is-invalid @enderror"
                            value="{{ old('firstname', $user->firstname) }}" required>
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="lastname" class="form-label fw-semibold">นามสกุล</label>
                        <input type="text" id="lastname" name="lastname"
                            class="form-control @error('lastname') is-invalid @enderror"
                            value="{{ old('lastname', $user->lastname) }}" required>
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary px-4">บันทึกข้อมูล</button>
            </form>
        </div>
    </div>
</x-layouts.app>
