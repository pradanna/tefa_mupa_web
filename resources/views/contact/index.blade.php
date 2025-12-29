<x-app-layout>

    {{-- 1. HERO HEADER --}}
    <section class="b-primary py-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('{{ asset('images/pattern-grid.png') }}'); opacity: 0.1;">
        </div>
        <div class="container position-relative py-5 text-center text-white">
            <h1 class="display-4 fw-bold" data-aos="fade-down">Hubungi Kami</h1>
            <p class="lead text-white-80" data-aos="fade-up" data-aos-delay="100">
                Punya pertanyaan atau ingin berkolaborasi? Kami siap membantu.
            </p>
        </div>
    </section>

    {{-- 2. INFO & FORM SECTION --}}
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row g-5">

                {{-- KOLOM KIRI: INFO KONTAK --}}
                <div class="col-lg-5" data-aos="fade-right">
                    <h5 class="fw-bold mb-4">Informasi Kontak</h5>

                    {{-- Item: Alamat --}}
                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box bg-light txt-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat Sekolah</h6>
                            <p class="text-muted small mb-0">
                                Jl. Pakem - Turi, Dsn. Sempu, Pakembinangun,<br>
                                Kec. Pakem, Kabupaten Sleman,<br>
                                Daerah Istimewa Yogyakarta 55582
                            </p>
                        </div>
                    </div>

                    {{-- Item: Email & Telepon --}}
                    <div class="d-flex gap-3 mb-4">
                        <div class="icon-box bg-light txt-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email & Telepon</h6>
                            <p class="text-muted small mb-0">tefa@smkmuhpakem.sch.id</p>
                            <p class="text-muted small mb-0">(0274) 123456</p>
                        </div>
                    </div>

                    {{-- Item: Jam Operasional --}}
                    <div class="d-flex gap-3 mb-5">
                        <div class="icon-box bg-light txt-primary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Jam Operasional</h6>
                            <p class="text-muted small mb-0">Senin - Jumat: 07.30 - 16.00 WIB</p>
                            <p class="text-muted small mb-0">Sabtu - Minggu: Tutup</p>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <h6 class="fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary rounded-circle"
                            style="width: 40px; height: 40px;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle"
                            style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-dark rounded-circle"
                            style="width: 40px; height: 40px;"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle"
                            style="width: 40px; height: 40px;"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM PESAN (Direct WA) --}}
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-light">
                        <h4 class="fw-bold mb-2">Kirim Pesan</h4>
                        <p class="text-muted mb-4">Silakan isi formulir di bawah ini, admin kami akan merespon via
                            WhatsApp.</p>

                        {{-- Form ini menggunakan AlpineJS untuk kirim ke WA --}}
                        <form x-data="contactForm()" @submit.prevent="sendMessage">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Nama Lengkap</label>
                                    <input type="text" x-model="name" class="form-control" placeholder="Nama Anda"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">No. WhatsApp</label>
                                    <input type="tel" x-model="phone" class="form-control" placeholder="08..."
                                        required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Kategori</label>
                                    <select x-model="category" class="form-select">
                                        <option value="Umum">Pertanyaan Umum</option>
                                        <option value="Pemesanan Produk">Pemesanan Produk</option>
                                        <option value="Layanan Jasa">Layanan Jasa</option>
                                        <option value="Kerjasama">Kerjasama Industri</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Pesan</label>
                                    <textarea x-model="message" class="form-control" rows="4" placeholder="Tulis pesan Anda disini..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn bt-primary w-100 py-3 fw-bold rounded-pill">
                                        <i class="bi bi-whatsapp me-2"></i> Kirim Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 3. MAP SECTION --}}
    <section class="bg-light py-5">
        <div class="container" data-aos="zoom-in">
            <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm border">
                {{-- Ganti src dengan Embed Map sekolah Anda --}}
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.1906013736493!2d110.412100661655!3d-7.662646392321988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5e6520f5d48f%3A0xfeb9bc7904298d01!2sSMK%20Muhammadiyah%20Pakem!5e0!3m2!1sid!2sid!4v1767000338463!5m2!1sid!2sid"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    {{-- Script Khusus Halaman Ini --}}
    @push('scripts')
        <script>
            function contactForm() {
                return {
                    name: '',
                    phone: '',
                    category: 'Umum',
                    message: '',
                    sendMessage() {
                        // Nomor Admin (Ganti dengan nomor asli)
                        const adminPhone = '6285865611145';

                        // Format Pesan
                        const text = `Halo Admin TEFA Mupa,%0A%0A` +
                            `Nama: ${this.name}%0A` +
                            `No. HP: ${this.phone}%0A` +
                            `Kategori: ${this.category}%0A%0A` +
                            `Pesan:%0A${this.message}`;

                        // Buka WhatsApp
                        window.open(`https://wa.me/${adminPhone}?text=${text}`, '_blank');
                    }
                }
            }
        </script>
    @endpush

</x-app-layout>
