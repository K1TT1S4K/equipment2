<x-layouts.app>
    <h3 class="text-dark">โปรไฟล์ของฉัน</h3>

    <div class="card border border-dark shadow-lg p-5 mb-3 rounded">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control border border-dark"
                        value="{{ $user->username }}" required>
                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                    <select name="prefix" class="form-control border border-dark" required>
                        @foreach ($prefixes as $prefix)
                            <option value="{{ $prefix->id }}" {{Auth::user()->prefix_id == $prefix->id ? 'selected' : ''}}>{{ $prefix->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                    <input type="text" name="firstname" class="form-control border border-dark"
                        value="{{ $user->firstname }}" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
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
                <div class="position-relative">
                    <label class="form-label">รหัสผ่านเก่า</label>
                    <input type="password" name="old_password" class="password-input form-control border border-dark">
                    <span onclick="togglePassword(0)"
                        style="position:absolute; right:8px; top:70%; transform:translateY(-50%); cursor:pointer;">

                        <!-- ไอคอนปิดตา (ซ่อน) -->
                        <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m15 18-.722-3.25" />
                            <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                            <path d="m20 15-1.726-2.05" />
                            <path d="m4 15 1.726-2.05" />
                            <path d="m9 18 .722-3.25" />
                        </svg>

                        <!-- ไอคอนตา (เปิด) -->
                        <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            style="display:none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                    </span>
                </div>
                @error('old_password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="password" class="password-input form-control border border-dark">
                <span onclick="togglePassword(1)"
                    style="position:absolute; right:8px; top:70%; transform:translateY(-50%); cursor:pointer;">

                    <!-- ไอคอนปิดตา (ซ่อน) -->
                    <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15 18-.722-3.25" />
                        <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                        <path d="m20 15-1.726-2.05" />
                        <path d="m4 15 1.726-2.05" />
                        <path d="m9 18 .722-3.25" />
                    </svg>

                    <!-- ไอคอนตา (เปิด) -->
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
            </div>

            <div class="mb-3">
                <div class="position-relative">
                    <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="password_confirmation"
                        class="password-input form-control border border-dark">
                    <span onclick="togglePassword(2)"
                        style="position:absolute; right:8px; top:70%; transform:translateY(-50%); cursor:pointer;">

                        <!-- ไอคอนปิดตา (ซ่อน) -->
                        <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m15 18-.722-3.25" />
                            <path d="M2 8a10.645 10.645 0 0 0 20 0" />
                            <path d="m20 15-1.726-2.05" />
                            <path d="m4 15 1.726-2.05" />
                            <path d="m9 18 .722-3.25" />
                        </svg>

                        <!-- ไอคอนตา (เปิด) -->
                        <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            style="display:none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                    </span>
                </div>
                @error('password')
                    <div class="text-danger mt-n3">รหัสผ่านใหม่ไม่ตรงกัน</div>
                @enderror
            </div>
            {{-- <div class="mb-3">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control border border-dark">
                @error('password')
                    <div class="text-danger">รหัสผ่านใหม่ไม่ตรงกัน</div>
                @enderror
            </div> --}}

            <div class="text-end"><button type="submit" class="btn btn-primary">บันทึก</button></div>
        </form>
    </div>

    <script>
        function togglePassword(idx) {
            const inputs = document.querySelectorAll('.password-input');
            const eyes = document.querySelectorAll('.icon-eye');
            const eyesOff = document.querySelectorAll('.icon-eye-off');

            if (inputs[idx].type === "password") {
                inputs[idx].type = "text";
                eyesOff[idx].style.display = "none";
                eyes[idx].style.display = "inline";
            } else {
                inputs[idx].type = "password";
                eyesOff[idx].style.display = "inline";
                eyes[idx].style.display = "none";
            }
        }
    </script>
</x-layouts.app>
