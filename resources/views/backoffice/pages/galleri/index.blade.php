<x-backoffice.layout.main>
    <x-backoffice.partials.breadcrumb title="Galleri" pretitle="Galleri" createUrl="{{ route('album.create') }}" createLabel="Tambah Gambar" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards" id="gallery-grid">
                @include('backoffice.pages.galleri.partials.gallery-cards', ['gallerys' => $gallerys])
            </div>

            @if ($gallerys->isEmpty())
                <div class="text-center text-secondary py-5" id="gallery-empty">
                    Belum ada gambar. Klik &quot;Tambah Gambar&quot; untuk mengunggah.
                </div>
            @endif

            @if ($gallerys->hasMorePages())
                <div class="d-flex justify-content-center mt-4" id="gallery-load-more-wrap">
                    <button type="button" class="btn btn-primary" id="gallery-load-more" data-next-url="{{ $gallerys->nextPageUrl() }}">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="gallery-load-more-spinner" role="status" aria-hidden="true"></span>
                        <span id="gallery-load-more-label">Muat lebih banyak</span>
                    </button>
                </div>
            @endif

            <div class="d-none justify-content-center mt-2 text-secondary small" id="gallery-end-hint">
                Semua gambar telah ditampilkan
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            var btn = document.getElementById('gallery-load-more');
            if (!btn) return;

            var grid = document.getElementById('gallery-grid');
            var spinner = document.getElementById('gallery-load-more-spinner');
            var label = document.getElementById('gallery-load-more-label');
            var wrap = document.getElementById('gallery-load-more-wrap');
            var endHint = document.getElementById('gallery-end-hint');
            var emptyEl = document.getElementById('gallery-empty');

            function setLoading(loading) {
                btn.disabled = loading;
                spinner.classList.toggle('d-none', !loading);
                label.textContent = loading ? 'Memuat…' : 'Muat lebih banyak';
            }

            btn.addEventListener('click', function () {
                var url = btn.getAttribute('data-next-url');
                if (!url) return;

                setLoading(true);
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                    .then(function (r) {
                        if (!r.ok) throw new Error('Gagal memuat data');
                        return r.json();
                    })
                    .then(function (data) {
                        if (emptyEl) emptyEl.classList.add('d-none');

                        var temp = document.createElement('div');
                        temp.innerHTML = data.html;
                        while (temp.firstChild) {
                            grid.appendChild(temp.firstChild);
                        }

                        if (data.has_more && data.next_page_url) {
                            btn.setAttribute('data-next-url', data.next_page_url);
                            setLoading(false);
                        } else {
                            btn.setAttribute('data-next-url', '');
                            if (wrap) wrap.classList.add('d-none');
                            if (endHint) {
                                endHint.classList.remove('d-none');
                                endHint.classList.add('d-flex');
                            }
                        }
                    })
                    .catch(function () {
                        setLoading(false);
                        label.textContent = 'Coba lagi';
                    });
            });
        })();
    </script>
    @endpush
</x-backoffice.layout.main>
