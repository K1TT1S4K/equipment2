<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/instrument-sans.css') }}">
</head>

<body class="bg-secondary text-dark d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        @if (Route::has('login'))
            @auth
                <script>
                    window.location.href = "{{ route('dashboard.index') }}";
                </script>
            @else
                @livewire('auth.login')
            @endauth
        @endif
    </div>

    <!-- Bootstrap JS (Optional for interactivity like modals, tooltips) -->
    <script src="{{ asset('js/jquery-3.5.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
