<x-app-layout>

    {{-- BREADCRUMB HEADER --}}
    <section class="b-primary py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb k">
                <ol class="breadcrumb mb-0 small breadcrumb-white">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-decoration-none text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}"
                            class="text-decoration-none text-white">Produk
                            & Jasa</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $product['nama'] }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">

                {{-- KOLOM KIRI: GAMBAR --}}
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="sticky-top" style="top: 100px; z-index: 1;">
                        <div class="ratio ratio-1x1 rounded-4 overflow-hidden shadow-sm bg-light border">
                            <img src="{{ asset($product['img']) }}" class="object-fit-cover"
                                alt="{{ $product['nama'] }}">

                            {{-- Badge Tipe --}}
                            <div class="position-absolute top-0 start-0 m-4">
                                <span
                                    class="badge {{ $product['tipe'] == 'Produk' ? 'bg-success' : 'bg-primary' }} fs-6 px-3 py-2 shadow-sm">
                                    {{ $product['tipe'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO & CTA --}}
                <div class="col-lg-6" data-aos="fade-left">

                    <h6 class="text-primary fw-bold text-uppercase mb-2">{{ $product['kategori'] }}</h6>
                    <h1 class="fw-bold display-6 mb-3">{{ $product['nama'] }}</h1>

                    {{-- Harga --}}
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark">
                            @if (is_numeric($product['harga']))
                                Rp {{ number_format($product['harga'], 0, ',', '.') }}
                            @else
                                {{ $product['harga'] }}
                            @endif
                        </h3>
                        {{-- Status --}}
                        <div class="d-flex align-items-center gap-2 text-success small fw-bold">
                            <i class="bi bi-check-circle-fill"></i> {{ $product['status'] }}
                        </div>
                    </div>

                    {{-- Deskripsi Pendek --}}
                    <p class="lead text-muted mb-4">
                        {{ $product['deskripsi_pendek'] }}
                    </p>

                    {{-- CTA BUTTONS --}}
                    <div class="d-grid gap-2 d-md-flex mb-5">
                        <a href="https://wa.me/623820655083?text=Halo%20Admin,%20saya%20mau%20pesan/tanya%20produk:%20{{ urlencode($product['nama']) }}"
                            target="_blank"
                            class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-whatsapp fs-5"></i>
                            Pesan Sekarang
                        </a>

                        <a href="#" class="btn btn-outline-secondary btn-lg rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-share"></i>
                        </a>
                    </div>

                    {{-- TAB INFORMASI (Deskripsi & Spesifikasi) --}}
                    <div class="card border-0 shadow-sm bg-light rounded-4">
                        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                            <ul class="nav nav-pills card-header-pills gap-2" id="productTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill fw-bold small px-4" id="desc-tab"
                                        data-bs-toggle="tab" data-bs-target="#desc" type="button"
                                        role="tab">Deskripsi</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill fw-bold small px-4" id="specs-tab"
                                        data-bs-toggle="tab" data-bs-target="#specs" type="button"
                                        role="tab">Spesifikasi</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content" id="productTabContent">
                                {{-- Tab Deskripsi --}}
                                <div class="tab-pane fade show active text-muted" id="desc" role="tabpanel">
                                    {!! $product['deskripsi_lengkap'] !!}
                                </div>

                                {{-- Tab Spesifikasi --}}
                                <div class="tab-pane fade" id="specs" role="tabpanel">
                                    <ul class="list-group list-group-flush bg-transparent">
                                        @foreach ($product['spesifikasi'] as $spec)
                                            <li
                                                class="list-group-item bg-transparent px-0 d-flex align-items-center gap-2">
                                                <i class="bi bi-dot text-primary fs-3"></i>
                                                {{ $spec }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: PRODUK LAINNYA --}}
    <section class="py-5 bg-light border-top">
        <div class="container">
            <h4 class="fw-bold mb-4">Produk & Layanan Lainnya</h4>

            <div class="row g-4">
                @foreach ($related as $item)
                    <div class="col-lg-4 col-md-6">
                        {{-- Reuse Component Card Product yang sudah dibuat --}}
                        <x-card-product :item="$item" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
