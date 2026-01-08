<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar">
        <div class="container-xl">
          <ul class="navbar-nav">
            @foreach ($menus as $menu)
              <li class="nav-item">
                <a href="{{ $menu['link'] ?? '#' }}"
                   class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded-2 hover-bg-primary hover-text-white transition position-relative"
                   style="transition: background 0.2s, color 0.2s; display: inline-flex; width: auto; min-width: 0; white-space: nowrap;">
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
