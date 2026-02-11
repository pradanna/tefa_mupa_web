<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar">
        <div class="container-xl">
          <ul class="navbar-nav">
            @foreach ($menus as $menu)
              @php
                // Pastikan semua link menu berupa absolute url (berawalan /)
                $link = $menu['link'] ?? '#';
                // Jika tidak ada http(s) dan tidak diawali dengan '/', tambahkan '/'
                if ($link !== '#' && !preg_match('~^(?:[a-z]+:)?//~i', $link) && substr($link, 0, 1) !== '/') {
                    $link = '/' . ltrim($link, '/');
                }
                $parsedLink = $link;
                // Tentukan apakah menu aktif
                $isActive = (request()->is(ltrim($parsedLink, '/').'*') || (isset($menu['backoffice']) && $menu['backoffice'] && request()->is('backoffice*')));
              @endphp
              <li class="nav-item">
                <a href="{{ $parsedLink }}"
                   class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded-2 hover-bg-primary hover-text-white transition position-relative{{ $isActive ? ' active' : '' }}"
                   style="transition: background 0.2s, color 0.2s; display: inline-flex; width: auto; min-width: 0; white-space: nowrap;{{ $isActive ? ' background: #f1f3f4; color: #303133;' : '' }}">
                  <span class="nav-link-title fw-semibold fs-5">
                    {{ $menu['title'] }}
                  </span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
</header>
