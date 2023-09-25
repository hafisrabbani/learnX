<li class="menu-item {{ str_starts_with(Route::current()->uri, 'dosen/mata-kuliah') ? 'active' : '' }}">
    <a href="{{ route('dosen.mata-kuliah') }}" class='menu-link'>
        <i class="menu-icon bi bi-book"></i>
        <span>Mata Kuliah</span>
    </a>
</li>

<li class="menu-item {{ str_starts_with(Route::current()->uri, 'forums') ? 'active' : '' }}">
    <a href="{{ route('forums.index') }}" class='menu-link'>
        <i class="menu-icon fas fa-comments"></i>
        <span>Forums</span>
    </a>
</li>

<li class="menu-item {{ str_starts_with(Route::current()->uri, 'absences') ? 'active' : '' }}">
    <a href="{{ route('dosen.absences.index') }}" class='menu-link'>
        <i class="menu-icon fas fa-comments"></i>
        <span>Absences</span>
    </a>
</li>
