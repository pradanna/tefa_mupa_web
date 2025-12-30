<x-app-layout>

    {{-- 1. HERO HEADER (Copy style dari Profile agar konsisten) --}}
    <section class="b-primary py-5 position-relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('{{ asset('images/pattern-grid.png') }}'); opacity: 0.1;">
        </div>

        <div class="container position-relative py-5 text-center text-white">
            <h1 class="display-4 fw-bold" data-aos="fade-down">Berita & Artikel</h1>
            <p class="lead text-white-80 mb-4" data-aos="fade-up" data-aos-delay="100">
                Informasi terbaru seputar kegiatan, prestasi, dan teknologi di {{ config('app.short_name') }}.
            </p>


        </div>
    </section>

    {{-- 2. FILTER SECTION (Floating Card effect) --}}
    <section class="mt-n5 position-relative z-2">
        <div class="container">
            <div class="card border-0 shadow-lg p-4 rounded-4" data-aos="fade-up">
                <form action="{{ route('news.index') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        {{-- Search Input --}}
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Cari Berita</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Judul artikel..." value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Date Input --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Filter Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="bt-primary w-100 fw-bold rounded-2 py-2 ">
                                Terapkan
                            </button>
                            @if (request()->has('search') || request()->has('date'))
                                <a href="{{ route('news.index') }}" class="btn btn-light border"
                                    data-bs-toggle="tooltip" title="Reset Filter">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- 3. NEWS GRID LIST --}}
    <section class="py-5 bg-light">
        <div class="container py-4">

            @if ($articles->isEmpty())
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-state.svg') }}" width="150" class="mb-3 opacity-50"
                        alt="Kosong">
                    <h4 class="text-muted">Berita tidak ditemukan</h4>
                    <p class="text-muted small">Coba ubah kata kunci atau tanggal pencarian Anda.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($articles as $item)
                        <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            <x-card-news :item="$item" />

                        </div>
                    @endforeach
                </div>

                {{-- 4. PAGINATION --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            @endif

        </div>
    </section>

</x-app-layout>
