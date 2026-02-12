@php
    // Definisikan struktur menu sidebar di sini
    // 'route' => nama route dari web.php
    // 'icon' => kelas ikon dari Bootstrap Icons (https://icons.getbootstrap.com/)
    // 'title' => Teks yang akan ditampilkan
    // 'pattern' => Pola URL untuk menentukan state 'active'
    $menus = [
        [
            'route' => 'dashboard',
            'icon' => 'layout-dashboard',
            'title' => 'Dashboard',
            'pattern' => 'backoffice/dashboard',
        ],
        // --- Header untuk grouping menu ---
        ['is_header' => true, 'title' => 'Manajemen Konten'],
        [
            'route' => 'sliders.index',
            'icon' => 'gallery-horizontal',
            'title' => 'Sliders',
            'pattern' => 'backoffice/sliders*',
        ],
        [
            'route' => 'categories.index',
            'icon' => 'tags',
            'title' => 'Kategori',
            'pattern' => 'backoffice/categories*',
        ],
        [
            'route' => 'articles.index',
            'icon' => 'newspaper',
            'title' => 'Berita',
            'pattern' => 'backoffice/berita*',
        ],
        [
            'route' => 'album.index',
            'icon' => 'gallery-thumbnails',
            'title' => 'Galeri',
            'pattern' => 'backoffice/galleries*',
        ],
        [
            'route' => 'catalog.index',
            'icon' => 'book-open-text',
            'title' => 'Katalog Produk',
            'pattern' => 'backoffice/catalog*',
        ],
        [
            'route' => 'promotions.index',
            'icon' => 'megaphone',
            'title' => 'Promosi',
            'pattern' => 'backoffice/promotions*',
        ],
        // --- Header untuk grouping menu ---
        ['is_header' => true, 'title' => 'Profil Sekolah'],
        [
            'route' => 'history.index',
            'icon' => 'history',
            'title' => 'Sejarah',
            'pattern' => 'backoffice/history*',
        ],
        [
            'route' => 'vision-missions.index',
            'icon' => 'gem',
            'title' => 'Visi & Misi',
            'pattern' => 'backoffice/vision-missions*',
        ],
        [
            'route' => 'organizations.index',
            'icon' => 'network',
            'title' => 'Struktur Organisasi',
            'pattern' => 'backoffice/organizations*',
        ],
        [
            'route' => 'licenses.index',
            'icon' => 'award',
            'title' => 'Lisensi',
            'pattern' => 'backoffice/licenses*',
        ],
        [
            'route' => 'partners.index',
            'icon' => 'handshake',
            'title' => 'Partner',
            'pattern' => 'backoffice/partners*',
        ],
    ];
@endphp

<aside class="sidebar-main bg-body border-end" style="width: 280px; min-height: 100vh;">
    <div class="p-3">
        {{-- Logo dan Nama Aplikasi --}}

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ asset('images/local/logo-tefa.png') }}" class="logo-navbar" />
        </a>
    </div>
    <hr class="m-0" />
    <nav class="sidebar-nav p-2">
        <ul class="nav flex-column">
            @foreach ($menus as $menu)
                @if (isset($menu['is_header']) && $menu['is_header'])
                    <li class="nav-item-header">{{ $menu['title'] }}</li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is($menu['pattern']) ? 'active' : '' }}"
                            href="{{ route($menu['route']) }}">
                            <i data-lucide="{{ $menu['icon'] }}"></i>
                            <span>{{ $menu['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
