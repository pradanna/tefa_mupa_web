@props(['item', 'type' => 'produk'])

@php
    $isJasa = $type === 'jasa';

    $badgeClass = $isJasa ? 'bg-success text-white' : 'bg-primary text-white';

    $btnClass = $isJasa ? 'btn-outline-success' : 'btn-outline-primary';
    $btnText = $isJasa ? 'Booking Jasa' : 'Lihat Detail';
    $detailUrl = route('products.show', $item['slug']);
@endphp

<a href="{{ $detailUrl }}" class="card h-100 border-0 shadow-sm hover-up group-card">

    {{-- BAGIAN GAMBAR --}}
    <div class="ratio ratio-4x3 overflow-hidden rounded-top position-relative">
        <img src="{{ asset($item['img']) }}" class="card-img-top object-fit-cover transition-img"
            alt="{{ $item['nama'] }}">

    </div>

    <div class="card-body position-relative pt-4">


        <span
            class=" {{ $badgeClass }} position-absolute top-0 start-0  translate-middle-y shadow-sm px-2 py-1 rounded-end-pill">
            {{ $item['kategori'] }}
        </span>

        {{-- KONTEN TEKS --}}
        <div class="mt-2 text-start">
            <h5 class="card-title fw-bold text-dark mb-2 lh-sm">{{ $item['nama'] }}</h5>

            <p class="card-text text-muted small mb-4 line-clamp-2">
                {{ $item['deskripsi'] ?? ($isJasa ? 'Layanan profesional dengan teknisi handal dan bergaransi.' : 'Produk unggulan hasil karya siswa berkompeten.') }}
            </p>
        </div>

    </div>
</a>
