<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';

    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', 'คุณได้ตั้งรหัสผ่านใหม่เรียบร้อยแล้ว');
        $this->redirectRoute('home', navigate: true);
    }
};

?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="min-width: 400px; max-width: 500px; width: 100%;">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">ตั้งรหัสผ่านใหม่</h4>
                <p class="text-muted small">กรุณากรอกรหัสผ่านใหม่ของคุณ</p>
            </div>

            {{-- Global Error List --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-auth-session-status class="mb-3 text-center" :status="session('status')" />

            <form wire:submit.prevent="resetPassword">
                <input type="hidden" wire:model="token" />

                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <div class="form-control-plaintext text-dark">{{ $email }}</div>
                    <input type="hidden" wire:model.defer="email" />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่านใหม่</label>
                    <input
                        wire:model.defer="password"
                        type="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="อย่างน้อย 8 ตัว"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                    <input
                        wire:model.defer="password_confirmation"
                        type="password"
                        id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="ยืนยันรหัสผ่าน"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove>ตั้งรหัสผ่านใหม่</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                            กำลังดำเนินการ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

