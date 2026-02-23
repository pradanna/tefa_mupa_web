<x-app-layout>
    <section class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
        <div class="container text-center">

            {{-- Icon atau Ilustrasi --}}
            <div class="mb-4 text-secondary opacity-50">
                <i class="bi bi-hourglass-bottom" style="font-size: 6rem;"></i>
            </div>

            {{-- Angka Error --}}
            <h1 class="display-1 fw-bold text-primary mb-2">419</h1>

            {{-- Pesan Error --}}
            <h3 class="fw-bold mb-3">Sesi Halaman Berakhir</h3>
            <p class="text-muted mb-5 mx-auto" style="max-width: 500px;">
                Maaf, sesi Anda telah kadaluarsa karena tidak ada aktivitas dalam waktu yang lama. Silakan muat ulang
                halaman untuk melanjutkan.
            </p>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-center gap-3">
                {{-- Tombol Kembali ke halaman sebelumnya --}}
                <a href="{{ url()->previous() }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class="bi bi-arrow-clockwise me-2"></i> Muat Ulang
                </a>

                {{-- Tombol Kembali ke Home --}}
                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-house me-2"></i> Beranda
                </a>
            </div>

        </div>
    </section>
</x-app-layout>
