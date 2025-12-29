@props(['item'])

<a href="{{ route('news.show', $item['slug']) }}"
    class="d-flex align-items-center gap-3 text-decoration-none group-card-sidebar py-2 border-bottom">

    {{-- Gambar Thumbnail (Kecil & Kotak) --}}
    <div class="flex-shrink-0 overflow-hidden rounded-3" style="width: 90px; height: 90px;">
        <img src="{{ asset($item['img']) }}" class="w-100 h-100 object-fit-cover transition-img"
            alt="{{ $item['judul'] }}">
    </div>

    {{-- Teks --}}
    <div class="flex-grow-1">
        <h6 class="fw-bold text-dark mb-1 small lh-sm line-clamp-2">
            {{ $item['judul'] }}
        </h6>
        <small class="text-muted" style="font-size: 0.8rem;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ \Carbon\Carbon::parse($item['tanggal'])->isoFormat('D MMM Y') }}
        </small>
    </div>
</a>
