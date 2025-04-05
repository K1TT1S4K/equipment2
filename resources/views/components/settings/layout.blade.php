<div class="d-flex flex-column flex-md-row">
    <!-- Sidebar เมนูการตั้งค่า -->
    <div class="me-md-4 w-100 pb-4" style="max-width: 220px;">
        <ul class="list-group">
            <li class="list-group-item">
                <a href="{{ route('settings.profile') }}" class="text-decoration-none">{{ __('Profile') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('settings.password') }}" class="text-decoration-none">{{ __('Password') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('settings.appearance') }}" class="text-decoration-none">{{ __('Appearance') }}</a>
            </li>
        </ul>
    </div>

    <!-- เส้นคั่นเฉพาะใน Mobile -->
    <hr class="d-md-none my-4" />

    <!-- Content ด้านขวา -->
    {{-- <div class="flex border border-dark shadow-lg p-3 mb-5 bg-body rounded p-4">
        <h3 class="mb-2 text-center">{{ $heading ?? '' }}</h3>
        <p class="text-muted">{{ $subheading ?? '' }}</p>

        <div class="mt-4 w-100" style="max-width: 600px;">
            {{ $slot }}
        </div>
    </div> --}}
    <div class="card border border-dark shadow-lg p-3 mb-5 bg-body rounded p-4">
        <h3 class="mb-2">{{ $heading ?? '' }}</h3>
        {{-- <p class="text-muted">{{ $subheading ?? '' }}</p> --}}

        <div class="mt-4 w-200" style="max-width: 600px;">
            {{ $slot }}
        </div>
    </div>
</div>
