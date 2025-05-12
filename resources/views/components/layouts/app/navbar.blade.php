<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-vh-100 bg-white text-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('image/RMUTI.png') }}" style="width: 30px; height: auto; display: block; margin: auto;" class="d-inline-block align-text-top">
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
                        <a class="nav-link active" href="{{ route('equipment.index') }}?title_filter=1&unit_filter=all&location_filter=all&user_filter=all">
                            ครุภัณฑ์
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('document.index') }}">
                            เอกสาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user') }}">
                            บุคลากร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('profile') }}">
                            โปรไฟล์
                        </a>
                    </li>
                    @can('can-view-retore')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('document.trash') }}">
                            ทดสอบ
                        </a>
                    </li>
                    <li><a class="nav-item" href="{{ route('user.trashed')}}">กู้คืนบุคลากร</a></li>
                    @endcan
                </ul>

                {{-- ปุ่มด้านขวา --}}
                <div class="d-flex align-items-center gap-3">
                    {{-- แสดงสถานะผู้ใช้ --}}
                    <div class="text-white">
                        <strong>{{ Auth::user()->user_type }}</strong>
                    </div>
                    {{-- กู้คืนข้อมูล --}}
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="recoveryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            กู้คืนข้อมูล
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="recoveryDropdown">
                            {{-- <li><a class="dropdown-item" href="#">กู้คืนครุภัณฑ์</a></li> --}}
                            <li><a class="dropdown-item" href="{{ route('document.trash') }}">กู้คืนเอกสาร</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.trashed')}}">กู้คืนบุคลากร</a></li>
                        </ul>
                    </div>

                    {{-- ลงชื่อออก --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>
