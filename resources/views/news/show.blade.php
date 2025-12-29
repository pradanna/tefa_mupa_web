<x-app-layout>

    {{-- HEADER KECIL (Breadcrumb Only) --}}
    <section class="b-primary py-4 position-relative">
        <div class="container position-relative z-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 breadcrumb-white">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class=" text-decoration-none text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}"
                            class=" text-decoration-none text-white">Berita</a></li>
                    <li class="breadcrumb-item active text-white text-truncate" style="max-width: 200px;"
                        aria-current="page">{{ $article['judul'] }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- KONTEN UTAMA --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">

                {{-- KOLOM KIRI (Artikel Utama) --}}
                <div class="col-lg-8">

                    {{-- Meta Info --}}
                    <div class="mb-3 d-flex gap-3 text-muted small">
                        <span
                            class="badge b-primary bg-opacity-10 text-white px-3 rounded-pill">{{ $article['kategori'] }}</span>
                        <span><i class="bi bi-calendar3 me-1"></i>
                            {{ \Carbon\Carbon::parse($article['tanggal'])->isoFormat('D MMMM Y') }}</span>
                    </div>

                    {{-- Judul Besar --}}
                    <h1 class="fw-bold mb-4 display-6 lh-sm">{{ $article['judul'] }}</h1>

                    {{-- Gambar Utama --}}
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm mb-5">
                        <img src="{{ asset($article['img']) }}" class="object-fit-cover" alt="{{ $article['judul'] }}">
                    </div>

                    {{-- Isi Artikel (Typography Article) --}}
                    <div class="article-content text-secondary lh-lg">
                        <p class="lead fw-medium text-dark">
                            {{-- Paragraf Pembuka (Excerpt) --}}
                            {{ $article['excerpt'] ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit.' }}
                        </p>

                        {{-- Dummy Content Panjang --}}
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui dicta minus molestiae vel
                            beatae natus eveniet ratione temporibus aperiam harum alias officiis assumenda officia
                            quibusdam deleniti eos cupiditate diam voluptatem.</p>

                        <h5>Sub Judul Artikel</h5>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                            laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                            architecto beatae vitae dicta sunt explicabo.</p>

                        <ul>
                            <li>Poin pembahasan satu</li>
                            <li>Poin pembahasan dua</li>
                            <li>Poin pembahasan tiga</li>
                        </ul>

                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                    </div>

                    {{-- Share Buttons (Optional) --}}
                    <div class="border-top mt-5 pt-4 d-flex align-items-center gap-2">
                        <span class="fw-bold small me-2">Bagikan:</span>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-success rounded-circle"><i
                                class="bi bi-whatsapp"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-info rounded-circle"><i
                                class="bi bi-twitter-x"></i></a>
                    </div>

                </div>

                {{-- KOLOM KANAN (Sidebar Sticky) --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">

                        {{-- Search Widget --}}
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light">
                            <h5 class="fw-bold mb-3">Cari Berita</h5>
                            <form action="{{ route('news.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control border-end-0"
                                        placeholder="Kata kunci...">
                                    <button class="btn btn-white border border-start-0 bg-white" type="submit"><i
                                            class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>

                        {{-- Section: Baca Juga --}}
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0">Baca Juga</h5>
                                <a href="{{ route('news.index') }}"
                                    class="text-decoration-none small fw-bold txt-primary">Lihat
                                    Semua</a>
                            </div>

                            <div class="d-flex flex-column gap-1">
                                @foreach ($related as $item)
                                    {{-- Panggil Component Sidebar --}}
                                    <x-card-news-sidebar :item="$item" />
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>
