<x-app-layout>

    {{-- 1. HERO HEADER (Konsisten) --}}
    <section class="b-primary py-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('{{ asset('images/pattern-grid.png') }}'); opacity: 0.1;">
        </div>
        <div class="container position-relative py-5 text-center text-white">
            <h1 class="display-4 fw-bold" data-aos="fade-down">Katalog Produk & Jasa</h1>
            <p class="lead text-white-80 mb-4" data-aos="fade-up" data-aos-delay="100">
                Solusi teknologi tepat guna dan layanan profesional karya TEFA Mupa.
            </p>
        </div>
    </section>

    {{-- 2. TAB FILTER SECTION --}}
    <section class="mt-n5 position-relative z-2 mb-5">
        <div class="container">
            <div class="d-flex justify-content-center" data-aos="fade-up">
                <div class="bg-white p-2 rounded-pill shadow-lg d-inline-flex">
                    {{-- Logic Active State berdasarkan URL parameter --}}

                    <a href="{{ route('products.index', ['kategori' => 'all']) }}"
                        class="btn btn-tab rounded-pill px-4 py-2 fw-bold {{ request('kategori') == 'all' || !request('kategori') ? 'active' : '' }}">
                        Semua
                    </a>

                    <a href="{{ route('products.index', ['kategori' => 'Produk']) }}"
                        class="btn btn-tab rounded-pill px-4 py-2 fw-bold {{ request('kategori') == 'Produk' ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>

                    <a href="{{ route('products.index', ['kategori' => 'Jasa']) }}"
                        class="btn btn-tab rounded-pill px-4 py-2 fw-bold {{ request('kategori') == 'Jasa' ? 'active' : '' }}">
                        <i class="bi bi-wrench me-1"></i> Jasa
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. PRODUCT GRID --}}
    <section class="py-4 bg-light min-vh-50">
        <div class="container">

            @if ($products->isEmpty())
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-cart.svg') }}" width="150" class="mb-3 opacity-50"
                        alt="Kosong">
                    <h4 class="text-muted">Item belum tersedia</h4>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($products as $item)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <x-card-product :item="$item" />
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </section>

</x-app-layout>
