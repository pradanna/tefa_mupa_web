<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Web Profile TEFA SMK Muhammadiyah Pakem">

    <title>{{ $title ?? 'TEFA SMK MUHAMMADYAH PAKEM' }}</title>

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

    <main class="flex-grow-1">
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
        <a href="{{ route('news.index') }}" class="sidebar-item">
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

    @stack('morejs')
</body>

</html>
