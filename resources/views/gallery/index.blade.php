<x-app-layout>

    {{-- HEADER --}}
    <section class="b-primary py-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('{{ asset('images/pattern-grid.png') }}'); opacity: 0.1;">
        </div>
        <div class="container position-relative py-5 text-center text-white">
            <h1 class="display-4 fw-bold" data-aos="fade-down">Galeri Kegiatan</h1>
            <p class="lead text-white-80" data-aos="fade-up" data-aos-delay="100">
                Dokumentasi aktivitas, fasilitas, dan karya siswa {{ config('app.short_name') }}.
            </p>
        </div>
    </section>

    {{-- MASONRY GALLERY SECTION --}}
    <section class="py-5 bg-light">
        <div class="container">

            {{-- Wrapper Masonry --}}
            <div class="masonry-grid">
                @foreach ($gallery as $item)
                    {{-- Item Gallery --}}
                    <div class="masonry-item mb-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative group-card">

                            {{-- Gambar (Tidak ada ratio fixed, biarkan auto) --}}
                            <img src="{{ asset($item['src']) }}" class="w-100 h-auto d-block transition-img"
                                alt="{{ $item['title'] }}">

                            {{-- Overlay Hover (Efek Pemanis) --}}
                            <div
                                class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-to-top opacity-0 hover-show transition-all">
                                <span class="badge bg-primary mb-2">{{ $item['category'] }}</span>
                                <h6 class="text-white fw-bold mb-0">{{ $item['title'] }}</h6>
                            </div>

                            {{-- Link Wrapper (Jika mau diklik zoom/lightbox) --}}
                            <a href="{{ asset($item['src']) }}" class="stretched-link glightbox"></a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

        {{-- Jika mau styling CSS khusus gallery ditaruh disini juga bisa --}}
        <style>
            .masonry-grid {
                column-count: 3;
                column-gap: 1.5rem;
            }

            @media (max-width: 992px) {
                .masonry-grid {
                    column-count: 2;
                }
            }

            @media (max-width: 576px) {
                .masonry-grid {
                    column-count: 1;
                }
            }

            .masonry-item {
                break-inside: avoid;
                margin-bottom: 1.5rem;
            }
        </style>
    @endpush

    @push('morejs')
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>

        <script>
            // Script ini hanya akan jalan di halaman gallery
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true
            });
        </script>
    @endpush

</x-app-layout>
