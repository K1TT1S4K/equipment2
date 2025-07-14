<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

return new #[Layout('components.layouts.auth')] class extends Component
{
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    #[Validate('required|email')]
    public string $email = '';

    public bool $showForgotPasswordForm = false;

    public function toggleForgotPasswordForm(): void
    {
        $this->resetValidation();
        $this->reset(['username', 'password', 'email']);
        $this->showForgotPasswordForm = ! $this->showForgotPasswordForm;

        if ($this->showForgotPasswordForm) {
            $this->dispatch('focusEmail');
        }
    }

    public function login(): void
{
    $this->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $this->ensureIsNotRateLimited();

    $loginField = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (!Auth::attempt([$loginField => $this->username, 'password' => $this->password], $this->remember)) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => __('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
    Session::regenerate();

    $this->redirectIntended(route('dashboard'), navigate: true);
}

    public function sendResetLink(): void
    {
        $this->validateOnly('email');

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
            $this->toggleForgotPasswordForm();
        } else {
            $this->addError('email', __($status));
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->username) . '|' . request()->ip();
    }
};
?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="row justify-content-center px-3">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg p-3 mb-5 bg-body rounded">
                <div class="card-body">
                    <img src="{{ asset('image/RMUTI.png') }}" style="width: 64px; display: block; margin: auto;">

                    <h5 class="card-title text-center mt-3">
                        ระบบแทงจำหน่ายครุภัณฑ์<br>สาขาเทคโนโลยีคอมพิวเตอร์
                    </h5>

                    @if (!$showForgotPasswordForm)
                        {{-- ฟอร์มล็อกอิน --}}
                        <form wire:submit.prevent="login">
                            <div class="form-group mt-3">
                                <label for="username">ชื่อผู้ใช้</label>
                                <input wire:model.defer="username" type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror" required
                                       autofocus autocomplete="username" placeholder="Your username" />
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">รหัสผ่าน</label>
                                <input wire:model.defer="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror" required
                                       autocomplete="current-password" placeholder="Password" />
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="login-button border50" wire:loading.attr="disabled">
                                    <span wire:loading.remove>เข้าสู่ระบบ</span>
                                    <span wire:loading>
                                        <div class="spinner-border spinner-border-sm text-light" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                </button>
                            </div>

                            <div class="text-center mt-2">
                                <a href="#" class="text-muted" wire:click.prevent="toggleForgotPasswordForm">
                                    ลืมรหัสผ่าน?
                                </a>
                            </div>
                        </form>
                    @else
                        {{-- ฟอร์มลืมรหัสผ่าน --}}
                        <form wire:submit.prevent="sendResetLink">
                            <div class="form-group mt-3">
                                <label for="email">อีเมลสำหรับรับลิงก์รีเซ็ตรหัสผ่าน</label>
                                <input wire:model.defer="email" type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror" required
                                       autocomplete="email" placeholder="Your email" x-ref="emailInput" />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>ส่งลิงก์รีเซ็ตรหัสผ่าน</span>
                                    <span wire:loading>
                                        <div class="spinner-border spinner-border-sm text-light" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                </button>
                            </div>

                            <div class="text-center mt-2">
                                <a href="#" class="text-muted" wire:click.prevent="toggleForgotPasswordForm">
                                    กลับไปหน้าเข้าสู่ระบบ
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Autofocus อีเมลเมื่อสลับฟอร์ม --}}
<script>
    document.addEventListener('livewire:load', () => {
        Livewire.on('focusEmail', () => {
            document.querySelector('[x-ref="emailInput"]')?.focus();
        });
    });
</script>
