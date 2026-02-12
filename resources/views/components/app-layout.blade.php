<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Web Profile TEFA SMK Muhammadiyah Pakem">
    <meta name="keywords"
        content="TEFA, SMK Muhammadiyah Pakem, Teaching Factory, Jasa, Produk, Pendidikan Vokasi, Sleman, Yogyakarta">
    <meta name="author" content="TEFA SMK Muhammadiyah Pakem">
    <meta name="robots" content="index, follow">

    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description"
        content="Official Web Profile TEFA SMK Muhammadiyah Pakem. Pusat keunggulan inovasi teknologi dan layanan jasa profesional.">
    <meta property="og:image" content="{{ asset('images/local/logo-tefa.png') }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? config('app.name') }}">
    <meta property="twitter:description"
        content="Official Web Profile TEFA SMK Muhammadiyah Pakem. Pusat keunggulan inovasi teknologi dan layanan jasa profesional.">
    <meta property="twitter:image" content="{{ asset('images/local/logo-tefa.png') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/fe-style.css') }}?v={{ filemtime(public_path('css/fe-style.css')) }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.0/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <x-navbar />

    <main class="grow">
        {{ $slot }}
    </main>

    {{-- FLOATING RIGHT SIDEBAR --}}
    <div class="floating-sidebar shadow-lg">

        {{-- Aksen Merah di Atas (Sesuai Referensi) --}}
        <div class="sidebar-top-accent"></div>

        {{-- Item 1: Kontak Kami --}}
        <a href="{{ route('contact.index') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="left"
            title="Hubungi Kami">
            <i class="bi bi-headset fs-4 mb-1"></i>
            <span>Kontak</span>
        </a>

        <div class="sidebar-divider"></div>

        {{-- Item 2: WhatsApp --}}
        <a href="https://wa.me/6285865611145?text=Halo%20Admin,%20saya%20mau%20bertanya..." target="_blank"
            class="sidebar-item">
            <i class="bi bi-whatsapp fs-4 mb-1 text-success"></i>
            <span>WhatsApp</span>
        </a>

        <div class="sidebar-divider"></div>

        {{-- Item 3: Promo (Bisa diarahkan ke Berita atau Filter Produk) --}}
        <a href="#" class="sidebar-item" data-bs-toggle="modal" data-bs-target="#promoModal"
            onclick="fetchPromos(); return false;">
            <i class="bi bi-tags fs-4 mb-1 text-danger"></i>
            <span>Promo</span>
        </a>

        <div class="sidebar-divider"></div>

        {{-- Item 4: Produk Kami --}}
        <a href="{{ route('products.index') }}" class="sidebar-item">
            <i class="bi bi-box-seam fs-4 mb-1 text-primary"></i>
            <span>Produk</span>
        </a>

    </div>

    <x-footer />

    {{-- Modal Promo --}}
    <div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-tags-fill me-2"></i>Promo Spesial</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4" id="promoContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-danger" role="status"></div>
                        <p class="mt-2 text-muted">Memuat promo...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });
    </script>

    <script>
        // Aktifkan semua tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <script>
        function fetchPromos() {
            const contentDiv = document.getElementById('promoContent');
            // Reset content loading
            contentDiv.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 text-muted">Memuat promo...</p>
                </div>
            `;

            fetch('{{ route('api.promos') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        contentDiv.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Yah, belum ada promo saat ini.</h5>
                                <p class="text-muted">Nantikan promo menarik lainnya segera!</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '<div class="row g-4">';
                    data.forEach(promo => {
                        html += `
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="position-relative">
                                        <img src="${promo.image}" class="card-img-top object-fit-cover" style="height: 200px;" alt="${promo.name}">
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <span class="badge bg-warning text-dark shadow-sm">
                                                <i class="bi bi-clock me-1"></i> Berakhir: ${promo.expired_formatted}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">${promo.name}</h5>
                                        <p class="card-text text-muted small">${promo.desc}</p>

                                        <div class="mt-3">
                                            <div class="d-grid">
                                                <button class="btn btn-outline-danger fw-bold btn-reveal-code"
                                                    onclick="this.classList.add('d-none'); this.nextElementSibling.classList.remove('d-none');">
                                                    Lihat Kode Promo
                                                </button>
                                                <div class="input-group d-none animate__animated animate__fadeIn">
                                                    <span class="input-group-text bg-danger text-white border-danger"><i class="bi bi-ticket-perforated"></i></span>
                                                    <input type="text" class="form-control text-center fw-bold text-danger bg-white" value="${promo.code}" readonly>
                                                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText('${promo.code}'); alert('Kode disalin!')">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    contentDiv.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    contentDiv.innerHTML = `
                        <div class="text-center py-5 text-danger">
                            <i class="bi bi-exclamation-circle fs-1"></i>
                            <p class="mt-2">Gagal memuat data promo.</p>
                        </div>
                    `;
                });
        }
    </script>

    @stack('morejs')
</body>

</html>
