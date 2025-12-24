<footer class="bg-dark text-white pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 justify-content-between">

            {{-- KOLOM 1: IDENTITAS --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-gear-wide-connected text-primary"></i> TEFA MUPA
                </h5>
                <p class="text-white-50 small">
                    Teaching Factory SMK Muhammadiyah Pakem. Pusat keunggulan inovasi teknologi dan layanan jasa
                    profesional berbasis standar industri.
                </p>
                <div class="d-flex gap-3 mt-3">
                    {{-- Social Media Icons --}}
                    <a href="#" class="text-white social-icon"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white social-icon"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white social-icon"><i class="bi bi-youtube fs-5"></i></a>
                    <a href="#" class="text-white social-icon"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            {{-- KOLOM 2: QUICK LINKS --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Tautan Cepat</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                    <li><a href="#" class="footer-link">Tentang Kami</a></li>
                    <li><a href="#" class="footer-link">Produk Unggulan</a></li>
                    <li><a href="#" class="footer-link">Layanan Jasa</a></li>
                    <li><a href="#" class="footer-link">Berita & Artikel</a></li>
                </ul>
            </div>

            {{-- KOLOM 3: KONTAK --}}
            <div class="col-lg-4 col-md-12">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled text-white-50 small d-flex flex-column gap-3">
                    <li class="d-flex gap-2">
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <span>Jl. Pakem - Turi, Pakembinangun, Kec. Pakem, Kabupaten Sleman, Daerah Istimewa Yogyakarta
                            55582</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-telephone-fill text-primary"></i>
                        <span>(0274) 895xxx (Kantor)</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-whatsapp text-primary"></i>
                        <span>+62 812-xxxx-xxxx (Admin TEFA)</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-envelope-fill text-primary"></i>
                        <span>tefa@smkmuhmpakem.sch.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-50">

        {{-- BOTTOM BAR: COPYRIGHT --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small text-white-50 mb-0">
                    &copy; {{ date('Y') }} <strong>TEFA SMK Muhammadiyah Pakem</strong>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end d-none d-md-block">
                <p class="small text-white-50 mb-0">
                    Designed with <i class="bi bi-heart-fill text-danger"></i> & Laravel 12
                </p>
            </div>
        </div>
    </div>
</footer>

{{-- STYLE TAMBAHAN KHUSUS FOOTER (Bisa dipindah ke style.css) --}}
<style>
    /* Efek hover pada link footer agar tidak kaku */
    .footer-link {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-link:hover {
        color: #0d6efd;
        /* Warna Primary Bootstrap */
        padding-left: 5px;
        /* Efek geser sedikit saat dihover */
    }

    /* Efek hover icon sosmed */
    .social-icon {
        opacity: 0.7;
        transition: opacity 0.3s;
    }

    .social-icon:hover {
        opacity: 1;
    }
</style>
