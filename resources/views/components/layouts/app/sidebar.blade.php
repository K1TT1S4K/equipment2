<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body class="min-vh-100 bg-white text-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/RMUTI.png') }}" style="width: 30px; height: auto; display: block; margin: auto;" class="d-inline-block align-text-top">
            </a>

            <!-- Navbar Toggle (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Menu -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                           แดชบอร์ด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active {{ request()->routeIs('equipments') ? 'active' : '' }}" href="{{ route('equipment.index') }}">
                           ครุภัณฑ์
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('document.index') }}">
                           เอกสาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('user') }}">
                           บุคลากร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('profile') }}">
                           โปรไฟล์
                        </a>
                    </li>
                    @can('manage-equipments')
                    <li class="nav-item">
                        <a class="nav-link active {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('user') }}">
                            <i class="bi bi-house-door"></i> ทดสอบ
                        </a>
                    </li>
                    @endcan
                </ul>

                <!-- Navbar Icons -->
                <div class="d-flex align-items-center btn btn-danger">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i> Log Out
                        </button>
                    </form>

                    <!-- User Dropdown -->
                    {{-- <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->initials() }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header text-center">
                                <strong>{{ auth()->user()->name }}</strong><br>
                                <small>{{ auth()->user()->email }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/settings/profile"><i class="bi bi-gear"></i> Settings</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
