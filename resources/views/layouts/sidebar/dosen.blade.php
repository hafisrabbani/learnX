<li class="menu-item {{ (str_starts_with(Route::current()->uri, 'dosen/mata-kuliah')) ? 'active' : '' }}">
    <a href="{{ route('dosen.mata-kuliah') }}" class='sidebar-link'>
        <i class="bi bi-book"></i>
        <span>Mata Kuliah</span>
    </a>
</li>
<li class="menu-item {{ (str_starts_with(Route::current()->uri, 'forums')) ? 'active' : '' }}">
    <a href="{{ route('forums.index') }}" class='menu-link'>
        <i class="fas fa-comments"></i>
        <span>Forums</span>
    </a>
</li>