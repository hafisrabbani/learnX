<li class="menu-item {{ request()->is('admin/user-manage') ? 'active' : '' }}">
    <a href="{{ route('user-manage.index') }}" class='menu-link'>
        <i class="menu-icon bi bi-person-lines-fill"></i>
        <span>Manage Users</span>
    </a>
</li>
<li class="menu-item {{ request()->is('admin/mata-kuliah') ? 'active' : '' }}">
    <a href="{{ route('mata-kuliah.index') }}" class='menu-link'>
        <i class="menu-icon bi bi-book"></i>
        <span>Mata Kuliah</span>
    </a>
</li>
<li class="menu-item {{ request()->is('admin/enrollment') ? 'active' : '' }}">
    <a href="{{ route('enrollment.index') }}" class='menu-link'>
        <i class="menu-icon bi bi-bookmark-check"></i>
        <span>Enrollment</span>
    </a>
</li>
