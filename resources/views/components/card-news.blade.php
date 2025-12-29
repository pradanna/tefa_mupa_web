@props(['item'])

{{-- Component Card Berita --}}
<a href="{{ route('news.show', $item['slug']) }}" class="card h-100 border-0 shadow-sm text-decoration-none group-card">

    {{-- Wrapper Gambar (biar bisa dikasih efek zoom/overflow hidden) --}}
    <div class="overflow-hidden rounded-top-3">
        <img src="{{ asset($item['img']) }}" class="card-img-top news-image object-fit-cover transition-img"
            style="height: 220px;" alt="{{ $item['judul'] }}">
    </div>

    <div class="card-body">
        {{-- Tanggal --}}
        <small class="text-muted d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event"></i>
            {{ \Carbon\Carbon::parse($item['tanggal'])->isoFormat('D MMMM Y') }}
        </small>

        {{-- Judul --}}
        <h5 class="card-title fw-bold mt-2 text-dark text-truncate">
            {{ $item['judul'] }}
        </h5>

        {{-- Excerpt (Dibatasi karakternya biar tinggi card rata) --}}
        <p class="card-text text-muted small line-clamp-3">
            {{ Str::limit($item['excerpt'], 100) }}
        </p>
    </div>
</a>
