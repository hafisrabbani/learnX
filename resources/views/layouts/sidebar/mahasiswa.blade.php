<li class="menu-item {{ str_starts_with(Route::current()->uri, 'mahasiswa/virtual-lab') ? 'active' : '' }}">
    <a href="{{ route('mahasiswa.virtual-lab.index') }}" class='menu-link'>
        <i class="menu-icon fas fa-vial text-primary"></i>
        <span>Virtual Lab</span>
    </a>
</li>
<li class="menu-item {{ str_starts_with(Route::current()->uri, 'forums') ? 'active' : '' }}">
    <a href="{{ route('forums.index') }}" class='menu-link'>
        <i class="menu-icon fas fa-comments text-primary"></i>
        <span>Forums</span>
    </a>
</li>

<li class="menu-item {{ str_starts_with(Route::current()->uri, 'mahasiswa/mata-kuliah') ? 'active' : '' }}">
    <a href="{{ route('mahasiswa.mata-kuliah.') }}" class='menu-link'>
        <i class="menu-icon fas fa-book-open text-primary"></i>
        <span>Mata Kuliah</span>
    </a>
</li>
