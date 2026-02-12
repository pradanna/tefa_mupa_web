<x-backoffice.layout.main>

    <x-slot:title>
        Dashboard
    </x-slot:title>

    {{-- Stats Cards --}}
    <div class="col-12">
        <div class="row row-cards">
            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="stat-card-icon bg-primary-lt">
                                    <i data-lucide="tags"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['categories'] }} Kategori
                                </div>
                                <div class="text-muted">
                                    Total kategori produk & berita
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="stat-card-icon bg-green-lt">
                                    <i data-lucide="book-open-text"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['catalogs'] }} Katalog Produk
                                </div>
                                <div class="text-muted">
                                    Jumlah produk & jasa
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="stat-card-icon bg-orange-lt">
                                    <i data-lucide="newspaper"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['news'] }} Berita
                                </div>
                                <div class="text-muted">
                                    Total artikel yang dipublikasi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Promotions & Latest News --}}
    <div class="col-lg-7 mt-3">
        <div class="card" style="height: calc(24rem + 10px)">
            <div class="card-header">
                <h3 class="card-title">Promosi Berjalan</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Nama Promosi</th>
                            <th>Kode</th>
                            <th class="text-end">Berakhir Dalam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($runningPromotions as $promo)
                            <tr>
                                <td>{{ $promo->name }}</td>
                                <td><span class="badge bg-purple-lt">{{ $promo->code }}</span></td>
                                <td class="text-end text-muted">
                                    {{ \Carbon\Carbon::parse($promo->expired)->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada promosi yang sedang
                                    berjalan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mt-3">
        <div class="card" style="height: calc(24rem + 10px)">
            <div class="card-header">
                <h3 class="card-title">Berita Terbaru</h3>
            </div>
            <div class="list-group list-group-flush overflow-auto">
                @forelse ($latestNews as $news)
                    <a href="{{ route('articles.edit', $news->id) }}"
                        class="list-group-item list-group-item-action list-group-item-news">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 text-truncate">{{ $news->title }}</h5>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($news->date)->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-muted text-truncate">
                            Kategori: {{ $news->category->name ?? 'Tanpa Kategori' }}
                        </p>
                    </a>
                @empty
                    <div class="list-group-item">
                        <p class="text-center text-muted m-0">Belum ada berita yang ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-backoffice.layout.main>
