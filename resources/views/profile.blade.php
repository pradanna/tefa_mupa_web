<x-app-layout>

    {{-- HERO SECTION: PROFIL --}}
    <section class="b-primary py-5 position-relative overflow-hidden">
        {{-- Background Pattern (Optional) --}}
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('{{ asset('images/pattern-grid.png') }}'); opacity: 0.1;">
        </div>

        <div class="container position-relative py-5 text-center text-white">
            <h1 class="display-4 fw-bold" data-aos="fade-down">TENTANG TEFA </h1>
            <h1 class="display-4 fw-bold" data-aos="fade-down">{{ config('app.holding_name') }}</h1>

        </div>
    </section>

    {{-- SECTION: SEJARAH --}}
    <section class="py-5" id="sejarah">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                {{-- Gambar Sejarah --}}
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="position-relative">
                        <div class="ratio ratio-4x3 rounded-4 overflow-hidden shadow-lg">
                            <img src="{{ asset('images/local/gedung.jpg') }}" class="object-fit-cover" alt="Sejarah">
                        </div>
                        {{-- Badge Tahun Berdiri --}}
                        <div
                            class="position-absolute bottom-0 start-0 bg-secondary text-dark p-4 rounded-top-right-4 shadow">
                            <h3 class="fw-bold mb-0">Est. 2018</h3>
                            <small>Tahun Berdiri</small>
                        </div>
                    </div>
                </div>

                {{-- Teks Narasi --}}
                <div class="col-lg-6" data-aos="fade-left">
                    <h6 class="txt-primary fw-bold text-uppercase ls-wide">Perjalanan Kami</h6>
                    <h2 class="fw-bold display-6 mb-4">Dedikasi untuk Pendidikan Vokasi Berkemajuan</h2>
                    <p class="text-muted mb-3">
                        {{ config('app.full_name_uppercase') }} didirikan sebagai respon terhadap kebutuhan
                        industri akan lulusan yang siap kerja. Bermula dari unit produksi kecil, kini kami telah
                        berkembang menjadi pusat pelatihan dan layanan jasa profesional.
                    </p>
                    <p class="text-muted mb-4">
                        Dengan dukungan fasilitas modern dan kurikulum yang selaras dengan industri (Link & Match), TEFA
                        Mupa berkomitmen mencetak teknisi handal dan wirausahawan muda yang berkarakter Islami.
                    </p>

                    {{-- Point Checklist (Optional) --}}
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Standar Industri Nasional</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Instruktur Tersertifikasi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: VISI & MISI --}}
    <section class="py-5 bg-light position-relative bg-tech-pattern" id="visi-misi">
        <div class="container py-lg-4">

            {{-- VISI (Center) --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <span class="badge bg-primary bg-opacity-10 txt-primary px-3 py-2 rounded-pill mb-3">VISI
                        KAMI</span>
                    <h2 class="fw-bold mb-4">"Menjadi Pusat Keunggulan Pendidikan Vokasi yang Menghasilkan Lulusan
                        Kompeten, Berkarakter, dan Berdaya Saing Global"</h2>
                </div>
            </div>

            {{-- MISI (Grid Cards) --}}
            <div class="row g-4 justify-content-center">
                {{-- Misi 1 --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm p-4 hover-up">
                        <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mb-3 txt-primary"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-mortarboard fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Pendidikan Berkualitas</h5>
                        <p class="text-muted small">Menyelenggarakan pembelajaran berbasis produksi yang selaras dengan
                            standar industri terkini.</p>
                    </div>
                </div>

                {{-- Misi 2 --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm p-4 hover-up">
                        <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mb-3 txt-primary"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Karakter & Etika</h5>
                        <p class="text-muted small">Membentuk insan profesional yang jujur, disiplin, dan memegang teguh
                            nilai-nilai keislaman.</p>
                    </div>
                </div>

                {{-- Misi 3 --}}
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm p-4 hover-up">
                        <div class="icon-box bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mb-3 txt-primary"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-briefcase fs-4"></i>
                        </div>
                        <h5 class="fw-bold">Kewirausahaan</h5>
                        <p class="text-muted small">Mengembangkan unit produksi sebagai wadah pelatihan wirausaha
                            mandiri bagi siswa.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- SECTION: STRUKTUR ORGANISASI --}}
    <section class="py-5" id="struktur-organisasi">
        <div class="container py-lg-4">

            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-secondary fw-bold text-uppercase">Tim Manajemen</h6>
                <h2 class="fw-bold">Struktur Organisasi {{ config('app.short_name') }}</h2>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($team as $member)
                    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card border-0 shadow-sm text-center h-100 overflow-hidden group-card">
                            {{-- Foto --}}
                            <div class="card-img-top overflow-hidden bg-light position-relative" style="height: 300px;">
                                {{-- Placeholder jika tidak ada foto --}}
                                <img src="{{ asset($member['foto']) }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member['nama']) }}&background=random&size=300'"
                                    class="w-100 h-100 object-fit-cover transition-img" alt="{{ $member['nama'] }}">

                                {{-- Social Overlay (Muncul saat hover) --}}
                                <div
                                    class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 p-3 translate-y-100 transition-all hover-show">
                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                                        <a href="#" class="text-white"><i class="bi bi-envelope"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body bg-white position-relative">
                                <h5 class="fw-bold mb-1">{{ $member['nama'] }}</h5>
                                <p class="text-primary small mb-0 fw-semibold">{{ $member['jabatan'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-app-layout>
