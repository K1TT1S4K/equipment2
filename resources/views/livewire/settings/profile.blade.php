<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">

    <x-settings.layout>
        <x-slot name="heading">
            <h2 class="font-semibold text-xl text-dark leading-tight">
                {{__('Profile')}}
            </h2>
        </x-slot>
        <form wire:submit.prevent="updateProfileInformation">
            <div class="row">
                <!-- ชื่อ -->
                <div class="mb-3 col-6">
                    <label for="name" class="form-label text-dark">{{ __('Username') }}</label>
                    <input type="text" id="name" name="name" class="form-control" wire:model="name" required autofocus autocomplete="name">
                </div>
                <div class="mb-3 col-6">
                    <label for="fname" class="form-label text-dark">{{ __('Prefix') }}</label>
                    <input type="text" id="name" name="name" class="form-control" required autofocus autocomplete="name">
                </div>
            </div>

            <div class="row">
                <!-- ชื่อ -->
                <div class="mb-3 col-6">
                    <label for="name" class="form-label text-dark">{{ __('First Name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" required autofocus autocomplete="name">
                </div>
                <div class="mb-3 col-6">
                    <label for="fname" class="form-label text-dark">{{ __('Last Name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" required autofocus autocomplete="name">
                </div>
            </div>

            <!-- อีเมล -->
            <div class="mb-3">
                <label for="email" class="form-label text-dark">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" class="form-control" wire:model="email" required autocomplete="email">

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <small class="text-danger">{{ __('Your email address is unverified.') }}</small>
                        <a href="#" class="text-primary text-decoration-none" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </a>

                        @if (session('status') === 'verification-link-sent')
                            <p class="text-success mt-2">{{ __('A new verification link has been sent to your email address.') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- ปุ่มบันทึก -->
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                <span class="text-success" wire:loading wire:target="updateProfileInformation">
                    {{ __('Saving...') }}
                </span>

                <span class="text-success" wire:loading.remove wire:target="updateProfileInformation">
                    {{ __('Saved.') }}
                </span>
            </div>
        </form>

        {{-- <livewire:settings.delete-user-form /> --}}
    </x-settings.layout>
</section>
