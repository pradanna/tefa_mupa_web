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
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">
                    <img src="{{ asset($slide['image']) }}" class="d-block w-100 hero-img" alt="{{ $slide['title'] }}">

                    {{-- Menghapus bg-dark dan bg-opacity, ganti ke text-start --}}
                    <div class="carousel-caption d-none d-md-block text-start hero-caption-clean">
                        <div class="container"> {{-- Container agar teks sejajar dengan konten navbar/body --}}
                            <h1 class="display-3 fw-bold animated-text">{{ $slide['title'] }}</h1>
                            <p class="fs-5 animated-text delay-1 mb-5">{{ $slide['subtitle'] }}</p>

                            {{-- Ganti btn-warning ke btn-primary atau class custom --}}
                            <a href="https://wa.me/6285865611145?text=Halo%2C%20saya%20mau%20info%20pendaftaran%20di%20SMK%20Muhammadiyah%20Pakem"
                                target="_blank" class="btn-tefa-primary btn-lg fw-bold px-4 py-4 animated-text delay-2">
                                Daftar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
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
    <section class="py-5 bg-about-glow">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="0">
                    <h6 class="txt-primary fw-bold text-uppercase ls-wide">Siapa Kami</h6>
                    <h2 class="fw-bold mb-4 display-6">{{ $profil['title'] }}</h2>
                    <p class="text-muted lead mb-4">{{ $profil['description'] }}</p>
                    <a href="{{ route('profile') }}" class="btn bt-outline-primary rounded-pill px-4">Baca Profil
                        Lengkap <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg">

                        <div class="ratio ratio-16x9">
                            <iframe src="https://drive.google.com/file/d/1b9ORrJ8v2g5W-WNC5qOAhcn0T4KYdhhJ/preview"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen title="Profile Video"
                                frameborder="0" class="object-fit-cover"></iframe>
                        </div>

                        {{-- <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div> --}}

                    </div>
                </div>

                {{--
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('images/local/gedung.jpg') }}" class="img-fluid rounded-4 shadow-lg w-100"
                        alt="Profil TEFA">
                </div> --}}
            </div>
        </div>
    </section>

    {{-- SECTION: KATEGORI LAYANAN --}}
    <section class="py-5 bg-light bg-tech-pattern ">
        <div class="container py-lg-4 mb-5">

            {{-- Section Title --}}
            <div class="text-center mb-5 mw-800 mx-auto">
                <h6 class="text-secondary fw-bold text-uppercase ls-wide" data-aos="fade-up" data-aos-delay="0">Lingkup
                    Layanan</h6>
                <h2 class="fw-bold display-6 mb-3" data-aos="fade-up" data-aos-delay="100">Solusi Komprehensif
                    {{ config('app.short_name') }}</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="200">
                    Kami menyediakan produk teknologi tepat guna dan layanan jasa profesional yang dikerjakan oleh siswa
                    berkompeten dengan standar industri.
                </p>
            </div>

            {{-- Grid Cards --}}
            <div class="row g-4">

                {{-- Card 1: Hardware --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card-category h-100 p-4 text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-cpu fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Hardware & IoT</h4>
                        <p class="text-muted mb-0">
                            Inovasi produk elektronik berbasis RFID seperti mesin absen otomatis, starter motor
                            otomatis, smart door lock.
                        </p>
                    </div>
                </div>

                {{-- Card 2: Creative & Design --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-category h-100 p-4 text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-vector-pen fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Desain & Kreatif</h4>
                        <p class="text-muted mb-0">
                            Layanan profesional untuk desain arsitektur bangunan, desain grafis, hingga pengembangan
                            solusi perangkat lunak.
                        </p>
                    </div>
                </div>

                {{-- Card 3: Service --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-category h-100 p-4 text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-tools fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Service & Repair</h4>
                        <p class="text-muted mb-0">
                            Pusat perbaikan terpercaya untuk Motor dan Mobil dengan teknisi terlatih dan bergaransi
                            layanan prima.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="container py-4 mt-5">
            <div class="text-center mb-3">
                <h2 class="fw-bold text-black " data-aos="fade-up" data-aos-delay="0">Produk & Layanan Kami</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Karya terbaik siswa dan layanan
                    profesional untuk masyarakat</p>
            </div>

            <div class="row g-4" x-data="{ activeTab: 'produk' }" data-aos="fade-up" data-aos-delay="200">

                {{-- Tombol Tab --}}
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button @click="activeTab = 'produk'"
                        :class="activeTab === 'produk' ? 'bt-primary' : 'bt-outline-primary'"
                        class="btn px-4 rounded-pill">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </button>
                    <button @click="activeTab = 'jasa'"
                        :class="activeTab === 'jasa' ? 'bt-primary' : 'bt-outline-primary'"
                        class="btn px-4 rounded-pill">
                        <i class="bi bi-tools me-1"></i> Jasa & Servis
                    </button>
                </div>


                {{-- LOOP PRODUK --}}
                @foreach ($produk as $item)
                    {{-- Gunakan x-show langsung di sini --}}
                    <div class="col-md-3 col-sm-6" x-show="activeTab === 'produk'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">

                        {{-- Panggil Component --}}
                        <x-card-product :item="$item" type="produk" />

                    </div>
                @endforeach

                {{-- LOOP JASA --}}
                @foreach ($jasa as $item)
                    <div class="col-md-3 col-sm-6" x-show="activeTab === 'jasa'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">

                        {{-- Panggil Component --}}
                        <x-card-product :item="$item" type="jasa" />

                    </div>
                @endforeach

            </div>

            <div class="text-end mt-3">
                <a href="{{ route('products.index') }}" class="bt-link-primary text-decoration-none fw-bold">Lihat
                    Semua Produk dan Jasa <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </section>

    {{-- SECTION: MITRA INDUSTRI --}}
    <section class="py-5 bg-white border-bottom">
        <div class="container">

            {{-- Judul Section (Optional) --}}
            <div class="text-center mb-4" data-aos="fade-up">
                <h6 class="text-secondary fw-bold text-uppercase ls-wide">Kepercayaan</h6>
                <h2 class="fw-bold">Mitra Industri Kami</h2>
            </div>


            <div class="row g-4 justify-content-center align-items-center">
                @foreach ($partners as $item)
                    <div class="col-4 col-sm-3 col-md-2" data-aos="zoom-in"
                        data-aos-delay="{{ $loop->index * 50 }}">

                        {{-- Wrapper untuk mengatur tinggi maksimal logo --}}
                        <div class="partner-logo-wrapper text-center">
                            <img src="{{ asset($item['img']) }}" class="img-fluid partner-logo transition-all"
                                alt="{{ $item['name'] }}" title="{{ $item['name'] }}">
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- SECTION 4: BERITA TERKINI --}}
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold txt-primary " data-aos="fade-up" data-aos-delay="0">Berita Terkini</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Berita terkini tentang
                    {{ config('app.holding_name') }}</p>
            </div>

            <div class="row g-4" data-aos="fade-up" data-aos-delay="200">
                @foreach ($berita as $news)
                    <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        <x-card-news :item="$news" />

                    </div>
                @endforeach
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('news.index') }}" class="bt-link-primary text-decoration-none fw-bold">Lihat Semua
                    Berita <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </section>

    {{-- SECTION 5: GALLERY --}}
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold txt-primary " data-aos="fade-up" data-aos-delay="0">Gallery Kegiatan</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Gallery kegiatan
                    {{ config('app.short_name') }}</p>
            </div>
            <div class="row g-2" data-aos="fade-up" data-aos-delay="200">
                @foreach ($gallery as $foto)
                    <div class="col-md-3 col-6">
                        <div class="ratio ratio-1x1 overflow-hidden rounded-3 shadow-sm gallery-item">
                            <img src="{{ asset($foto) }}" class="w-100 h-100 object-fit-cover" alt="Gallery">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('gallery.index') }}" class="bt-link-primary text-decoration-none fw-bold">Lihat
                    Semua Gallery <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </section>

    {{-- SECTION: CTA --}}
    <section class="py-5 b-primary text-white position-relative overflow-hidden">
        {{-- Background Pattern Halus (Optional) --}}
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="opacity: 0.1; background-image: radial-gradient(white 1px, transparent 1px); background-size: 20px 20px;">
        </div>

        <div class="container position-relative text-center py-5">
            <h2 class="fw-bold display-6 mb-3" data-aos="fade-up">Siap Berkolaborasi dengan
                {{ config('app.short_name') }}</h2>
            <p class="lead mb-4 mw-600 mx-auto text-white-80" data-aos="fade-up" data-aos-delay="100">
                Dapatkan layanan jasa profesional dan produk inovatif karya anak bangsa. Hubungi kami sekarang untuk
                konsultasi gratis.
            </p>
            <div data-aos="zoom-in" data-aos-delay="200">
                <a href="https://wa.me/6285865611145" target="_blank"
                    class="btn btn-light btn-lg rounded-pill fw-bold px-5 py-3 shadow mt-5">
                    <i class="bi bi-whatsapp me-2 text-success"></i> Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </section>

    @push('morejs')
        <script></script>
    @endpush
</x-app-layout>
