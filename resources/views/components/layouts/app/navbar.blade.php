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
                <img src="{{ asset('image/RMUTI.png') }}" style="width: 30px; height: auto; display: block; margin: auto;"
                    class="d-inline-block align-text-top">
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
                        <a class="nav-link active"
                            href="{{ route('equipment.index') }}?title_filter=1&unit_filter=all&location_filter=all&user_filter=all">
                            ครุภัณฑ์
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('document.index') }}">
                            เอกสาร
                        </a>
                    </li>
                    @can('admin')
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('user') }}">
                                บุคลากร
                            </a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('profile') }}">
                            โปรไฟล์
                        </a>
                    </li>
                </ul>

                {{-- ปุ่มด้านขวา --}}
                <div class="d-flex align-items-center gap-3">
                    {{-- แสดงสถานะผู้ใช้ --}}
                    <div class="text-white">
                        <strong>{{ Auth::user()->user_type }}</strong>
                    </div>

                    {{-- ปุ่มกู้คืนข้อมูล --}}
                    @if (Auth::check() && in_array(Auth::user()->user_type, ['ผู้ดูแลระบบ', 'เจ้าหน้าที่สาขา']))
                        <div class="dropdown">
                            <button class="btn btn-warning dropdown-toggle" type="button" id="recoveryDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                กู้คืนข้อมูล
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="recoveryDropdown">
                                {{-- กู้คืนเอกสาร ทุกสิทธิ์ที่ได้เข้ามา --}}
                                <li><a class="dropdown-item" href="{{ route('document.trash') }}">กู้คืนเอกสาร</a></li>

                                {{-- กู้คืนบุคลากร เฉพาะผู้ดูแลระบบ --}}
                                @can('admin')
                                    <li><a class="dropdown-item" href="{{ route('user.trashed') }}">กู้คืนบุคลากร</a></li>
                                @endcan

                                {{-- ตัวอย่างสำหรับอนาคต --}}
                                {{-- @can('admin-or-branch')
                                    <li><a class="dropdown-item" href="{{ route('equipment.trash') }}">กู้คืนครุภัณฑ์</a></li>
                                @endcan --}}
                            </ul>
                        </div>
                    @endif

                    {{-- ปุ่มออกจากระบบ --}}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
