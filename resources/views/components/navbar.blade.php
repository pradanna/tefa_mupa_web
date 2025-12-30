<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-4">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2 text-black" href="{{ route('home') }}">
            <img src="{{ asset('images/local/logo-tefa.png') }}" class="logo-navbar" /> <span class="title-navbar">TEFA SMK
                MUHAMMADYAH PAKEM</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        About Us
                    </a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('profile') }}#visi-misi">Visi Misi</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile') }}#struktur-organisasi">Struktur
                                Organisasi</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile') }}#sejarah">Sejarah</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Produk & Jasa</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('news.index') }}">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery.index') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact.index') }}">Contact Us</a></li>

            </ul>
        </div>
    </div>
</nav>
