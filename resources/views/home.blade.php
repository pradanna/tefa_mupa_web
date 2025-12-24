<x-app-layout title="Home - TEFA SMK Muhammadiyah Pakem">

    {{-- SECTION 1: HERO SLIDER --}}
    <header id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        {{-- Indikator (Titik-titik di bawah) - Opsional biar keren --}}
        <div class="carousel-indicators">
            @foreach ($hero as $index => $slide)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $index == 0 ? 'active' : '' }}" aria-current="true"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach ($hero as $index => $slide)
                {{-- Tambahkan data-bs-interval agar auto-play per slide --}}
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">

                    {{-- Style inline height dipindah ke SCSS nanti --}}
                    <img src="{{ $slide['image'] }}" class="d-block w-100 hero-img" alt="{{ $slide['title'] }}">

                    {{-- Caption dengan class custom 'hero-caption' --}}
                    <div class="carousel-caption d-none d-md-block hero-caption p-4 rounded-3">
                        <h1 class="display-4 fw-bold animated-text">{{ $slide['title'] }}</h1>
                        <p class="lead animated-text delay-1">{{ $slide['subtitle'] }}</p>
                        <a href="#"
                            class="btn btn-warning fw-bold px-4 py-2 mt-2 animated-text delay-2">Selengkapnya</a>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </header>

    {{-- SECTION 2: PROFIL SINGKAT --}}
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h6 class="text-primary fw-bold text-uppercase ls-wide">Siapa Kami</h6>
                    <h2 class="fw-bold mb-4 display-6">{{ $profil['title'] }}</h2>
                    <p class="text-muted lead mb-4">{{ $profil['description'] }}</p>
                    <a href="#" class="btn btn-outline-primary rounded-pill px-4">Baca Profil Lengkap <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ $profil['image'] }}" class="img-fluid rounded-4 shadow-lg w-100" alt="Profil TEFA">
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3: PRODUK & JASA (TABBED) --}}
    {{-- Menggunakan Alpine.js (x-data) untuk handle Tab --}}
    <section class="py-5 bg-light" x-data="{ activeTab: 'produk' }">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Produk & Layanan Kami</h2>
                <p class="text-muted">Karya terbaik siswa dan layanan profesional untuk masyarakat</p>

                {{-- Tombol Tab --}}
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button :class="activeTab === 'produk' ? 'btn-primary' : 'btn-outline-primary'"
                        class="btn px-4 rounded-pill">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </button>
                    <button :class="activeTab === 'jasa' ? 'btn-primary' : 'btn-outline-primary'"
                        class="btn px-4 rounded-pill">
                        <i class="bi bi-tools me-1"></i> Jasa & Servis
                    </button>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="row g-4">
                {{-- Loop Produk --}}
                <template x-if="activeTab === 'produk'">
                    @foreach ($produk as $item)
                        <div class="col-md-3 col-sm-6 animate-fade">
                            <div class="card h-100 border-0 shadow-sm hover-up">
                                <img src="{{ $item['img'] }}" class="card-img-top" alt="...">
                                <div class="card-body text-center">
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info mb-2">{{ $item['kategori'] }}</span>
                                    <h5 class="card-title fw-bold">{{ $item['nama'] }}</h5>
                                    <p class="card-text text-muted small">Deskripsi singkat produk unggulan kami.</p>
                                    <a href="#" class="btn btn-sm btn-primary w-100">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </template>

                {{-- Loop Jasa --}}
                <template x-if="activeTab === 'jasa'">
                    @foreach ($jasa as $item)
                        <div class="col-md-3 col-sm-6 animate-fade">
                            <div class="card h-100 border-0 shadow-sm hover-up">
                                <img src="{{ $item['img'] }}" class="card-img-top" alt="...">
                                <div class="card-body text-center">
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success mb-2">{{ $item['kategori'] }}</span>
                                    <h5 class="card-title fw-bold">{{ $item['nama'] }}</h5>
                                    <p class="card-text text-muted small">Layanan profesional dengan teknisi handal.</p>
                                    <a href="#" class="btn btn-sm btn-success w-100">Booking</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </template>
            </div>

            <div class="text-center mt-5">
                <a href="#" class="btn btn-link text-decoration-none fw-bold">Lihat Semua Katalog <i
                        class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </section>

    {{-- SECTION 4: BERITA TERKINI --}}
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Berita Terkini</h2>
                <a href="#" class="btn btn-outline-dark btn-sm rounded-pill">Lihat Semua</a>
            </div>

            <div class="row g-4">
                @foreach ($berita as $news)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $news['img'] }}" class="card-img-top rounded-top-3" alt="...">
                            <div class="card-body">
                                <small class="text-muted"><i class="bi bi-calendar-event"></i>
                                    {{ $news['tanggal'] }}</small>
                                <h5 class="card-title fw-bold mt-2 text-truncate">{{ $news['judul'] }}</h5>
                                <p class="card-text text-muted">{{ $news['excerpt'] }}</p>
                                <a href="#"
                                    class="text-primary text-decoration-none fw-semibold stretched-link">Baca
                                    Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SECTION 5: GALLERY --}}
    <section class="py-5 bg-light">
        <div class="container py-4">
            <h2 class="fw-bold text-center mb-5">Galeri Kegiatan</h2>
            <div class="row g-2">
                @foreach ($gallery as $foto)
                    <div class="col-md-3 col-6">
                        <div class="ratio ratio-1x1">
                            <img src="{{ $foto }}" class="img-fluid rounded-3 object-fit-cover shadow-sm"
                                alt="Gallery">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-primary rounded-pill">Lihat Galeri Penuh</a>
            </div>
        </div>
    </section>

    @push('morejs')
        <script></script>
    @endpush
</x-app-layout>

{{-- Script tambahan khusus halaman Home untuk efek/style --}}
<style>
    .hover-up {
        transition: transform 0.3s;
    }

    .hover-up:hover {
        transform: translateY(-5px);
    }

    .object-fit-cover {
        object-fit: cover;
    }

    /* Animasi Simpel saat ganti Tab */
    .animate-fade {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
