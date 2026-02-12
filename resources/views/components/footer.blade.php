<footer class="bg-dark text-white pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 justify-content-between">

            {{-- KOLOM 1: IDENTITAS --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
                    <img src="{{ asset('images/local/logo-tefa.png') }}" style="width: 50px" />
                    {{ config('app.name') }}
                </h5>
                <p class="text-white-50 small">
                    {{ config('app.full_name_uppercase') }}. Pusat keunggulan inovasi teknologi dan layanan jasa
                    profesional berbasis standar industri.
                </p>
                <div class="d-flex gap-3 mt-3">
                    {{-- Social Media Icons --}}
                    <a href="https://www.instagram.com/smkmuhpakem" target="_blank" class="text-white social-icon"><i
                            class="bi bi-instagram fs-5"></i></a>
                    <a href="https://www.facebook.com/smkmuhammadiyahpakem " target="_blank"
                        class="text-white social-icon"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="https://www.youtube.com/@SMKMuhammadiyahPakem" target="_blank"
                        class="text-white social-icon"><i class="bi bi-youtube fs-5"></i></a>
                    <a href="https://wa.me/6285865611145" target="_blank" class="text-white social-icon"><i
                            class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Tautan Cepat</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li>
                        <a href="{{ route('home') }}" class="footer-link text-decoration-none">Beranda</a>
                    </li>

                    <li>
                        <a href="{{ route('profile') }}" class="footer-link text-decoration-none">Tentang Kami</a>
                    </li>

                    <li>
                        <a href="{{ route('products.index', ['kategori' => 'Produk']) }}"
                            class="footer-link text-decoration-none">Produk Unggulan</a>
                    </li>

                    <li>
                        <a href="{{ route('products.index', ['kategori' => 'Jasa']) }}"
                            class="footer-link text-decoration-none">Layanan Jasa</a>
                    </li>

                    <li>
                        <a href="{{ route('news.index') }}" class="footer-link text-decoration-none">Berita &
                            Artikel</a>
                    </li>

                    <li>
                        <a href="{{ route('gallery.index') }}" class="footer-link text-decoration-none">Galeri</a>
                    </li>

                    <li>
                        <a href="#" class="footer-link text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#licenseModal" onclick="fetchLicenses(); return false;">Lisensi Sekolah</a>
                    </li>

                    <li>
                        <a href="{{ route('contact.index') }}" class="footer-link text-decoration-none">Hubungi Kami</a>
                    </li>
                </ul>
            </div>

            {{-- KOLOM 3: KONTAK --}}
            <div class="col-lg-4 col-md-12">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled text-white-50 small d-flex flex-column gap-3">
                    <li class="d-flex gap-2">
                        <i class="bi bi-geo-alt-fill txt-primary mt-1"></i>
                        <a href="https://maps.app.goo.gl/mMuCE3xkViSun2qh6" class="footer-link ">Jl. Pakem - Turi,
                            Pakembinangun, Kec. Pakem, Kabupaten Sleman, Daerah Istimewa Yogyakarta
                            55582</a>
                    </li>
                    {{-- <li class="d-flex gap-2">
                        <i class="bi bi-telephone-fill txt-primary"></i>
                        <span>(0274) 895xxx (Kantor)</span>
                    </li> --}}
                    <li class="d-flex gap-2">
                        {{-- Gunakan target="_blank" agar membuka tab baru --}}
                        <a href="https://wa.me/6285865611145" target="_blank" class="footer-link ">
                            <i class="bi bi-whatsapp txt-primary"></i>
                            <span class="ms-1">0858-6561-1145</span>
                        </a>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-envelope-fill txt-primary"></i>
                        <a href="mailto:stm_muhpakem@yahoo.co.id" class="footer-link ">stm_muhpakem@yahoo.co.id</a>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-globe txt-primary"></i>
                        <a href="https://smkmuhpakem.sch.id/" class="footer-link ">smkmuhpakem.sch.id</a>
                    </li>
                </ul>
            </div>
        </div>


        <hr class="border-secondary my-4 opacity-50">

        {{-- BOTTOM BAR: COPYRIGHT --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small text-white-50 mb-0">
                    &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end d-none d-md-block">
                <p class="small text-white-50 mb-0">
                    Designed with <i class="bi bi-heart-fill text-danger"></i> & Laravel 12
                </p>
            </div>
        </div>
    </div>

    {{-- MODAL LISENSI --}}
    <div class="modal fade" id="licenseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-award-fill me-2"></i>Lisensi & Sertifikasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <div id="licenseContent" class="row g-4">
                        {{-- Content will be loaded here --}}
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Memuat data lisensi...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT FETCH LICENSE --}}
    <script>
        function fetchLicenses() {
            const contentDiv = document.getElementById('licenseContent');

            // Jangan reload jika sudah ada isinya (opsional, hapus if ini jika ingin selalu reload)
            if (contentDiv.getAttribute('data-loaded') === 'true') return;

            fetch('{{ route('api.licenses') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        contentDiv.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-info-circle fs-1 text-muted"></i>
                                <p class="mt-2 text-muted">Belum ada data lisensi.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        // Tentukan tampilan jika PDF atau Gambar
                        let mediaDisplay = item.is_pdf ?
                            `<div class="ratio ratio-4x3 bg-white d-flex align-items-center justify-content-center border rounded-3">
                                 <div class="text-center p-3">
                                    <i class="bi bi-file-earmark-pdf text-danger display-4"></i>
                                    <p class="small text-muted mb-0 mt-2">Klik untuk melihat PDF</p>
                                 </div>
                               </div>` :
                            `<div class="ratio ratio-4x3 overflow-hidden rounded-3 border">
                                 <img src="${item.file_url}" class="w-100 h-100 object-fit-contain bg-white" alt="${item.name}">
                               </div>`;

                        html += `
                            <div class="col-md-6 col-lg-4">
                                <a href="${item.file_url}" target="_blank" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                        <div class="card-body p-3">
                                            ${mediaDisplay}
                                            <h6 class="fw-bold text-dark mt-3 mb-1 text-truncate" title="${item.name}">${item.name}</h6>
                                            <span class="badge bg-secondary small">${item.type}</span>
                                            <small class="d-block text-muted mt-1" style="font-size: 0.8rem;">${item.code}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        `;
                    });
                    contentDiv.innerHTML = html;
                    contentDiv.setAttribute('data-loaded', 'true');
                })
                .catch(error => {
                    console.error('Error:', error);
                    contentDiv.innerHTML = `<div class="col-12 text-center text-danger py-4">Gagal memuat data.</div>`;
                });
        }
    </script>
</footer>
