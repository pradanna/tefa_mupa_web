<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="bi bi-gear-wide-connected"></i> TEFA MUPA
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        About Us
                    </a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Visi Misi</a></li>
                        <li><a class="dropdown-item" href="#">Struktur Organisasi</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sejarah</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="#">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Gallery</a></li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light text-primary ms-2 px-3 fw-semibold" href="#">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
