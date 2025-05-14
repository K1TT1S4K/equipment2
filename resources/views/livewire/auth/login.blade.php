<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="row justify-content-center px-3">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg p-3 mb-5 bg-body rounded">
                <div class="card-body">
                    <img src="{{ asset('image/RMUTI.png') }}"
                        style="width: 64px; height: auto; display: block; margin: auto;">

                    <h5 class="card-title text-center mt-3">
                        ระบบแทงจำหน่ายครุภัณฑ์<br>
                        สาขาเทคโนโลยีคอมพิวเตอร์
                    </h5>

                    <form wire:submit.prevent="login">
                        <!-- Email Address -->
                        <div class="form-group mt-3">
                            <label for="email">Email address</label>
                            <input wire:model.defer="email" type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" required autofocus
                                autocomplete="email" placeholder="email@example.com" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <input wire:model.defer="password" type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="current-password" placeholder="Password" />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mt-3">
                            <input wire:model="remember" type="checkbox" class="form-check-input" id="remember" />
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove>Log in</span>
                                <span wire:loading>Loading...</span>
                            </button>
                        </div>

                        <!-- Forgot Password -->
                        {{-- @if (Route::has('password.request'))
                            <div class="text-center mt-2">
                                <a href="{{ route('password.request') }}" class="text-muted">Forgot your password?</a>
                            </div>
                        @endif --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
