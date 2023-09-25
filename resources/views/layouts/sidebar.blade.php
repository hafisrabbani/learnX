<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo2.png') }}" alt="Brand Logo" class="img-fluid mb-2" style="width: 35px; height: auto;">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">LearnX</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class='menu-link'>
                <i class="menu-icon bi bi-grid-fill text-primary"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @php $role = Auth::user()->role; @endphp
        @if ($role == 'admin')
            @include('layouts.sidebar.admin')
        @elseif($role == 'dosen')
            @include('layouts.sidebar.dosen')
        @elseif($role == 'mahasiswa')
            @include('layouts.sidebar.mahasiswa')
        @endif
        <li class="menu-item">
            <a href=" {{ route('logout') }}" class='menu-link'>
                <i class="menu-icon fas fa-sign-out-alt text-primary"></i>
                <span>Log Out</span>
            </a>
        </li>
    </ul>
</aside>
