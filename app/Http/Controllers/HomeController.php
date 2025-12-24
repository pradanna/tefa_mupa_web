<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Simulasi Data (Nanti diganti query Database)
        $data = [
            'hero' => [
                ['title' => 'Inovasi Tanpa Batas', 'subtitle' => 'Mencetak Generasi Unggul di Era Teknologi', 'image' => 'https://placehold.co/1200x600/0d6efd/ffffff?text=Slide+1'],
                ['title' => 'TEFA Mupa', 'subtitle' => 'Solusi Kebutuhan Industri dan Jasa', 'image' => 'https://placehold.co/1200x600/6610f2/ffffff?text=Slide+2'],
            ],
            'profil' => [
                'title' => 'Tentang TEFA Mupa',
                'description' => 'Teaching Factory SMK Muhammadiyah Pakem adalah pusat pengembangan kompetensi siswa berbasis industri. Kami menghadirkan produk teknologi tepat guna dan layanan profesional.',
                'image' => 'https://placehold.co/600x400/gray/white?text=Foto+Gedung',
            ],
            // Data dipisah agar mudah dimapping di Tabs
            'produk' => [
                ['nama' => 'Smart RFID Lock', 'kategori' => 'IoT', 'img' => 'https://placehold.co/300x200?text=RFID'],
                ['nama' => 'Running Text LED', 'kategori' => 'Elektronika', 'img' => 'https://placehold.co/300x200?text=LED'],
            ],
            'jasa' => [
                ['nama' => 'Servis Laptop & PC', 'kategori' => 'Teknisi', 'img' => 'https://placehold.co/300x200?text=Servis'],
                ['nama' => 'Desain Arsitektur', 'kategori' => 'Desain', 'img' => 'https://placehold.co/300x200?text=Arsitek'],
            ],
            'berita' => [
                ['judul' => 'Kunjungan Industri 2025', 'tanggal' => '24 Des 2025', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'https://placehold.co/400x250'],
                ['judul' => 'Juara 1 Lomba Robotik', 'tanggal' => '20 Des 2025', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'https://placehold.co/400x250'],
                ['judul' => 'Workshop IoT Gratis', 'tanggal' => '15 Des 2025', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'https://placehold.co/400x250'],
            ],
            'gallery' => [
                'https://placehold.co/300x300?text=1',
                'https://placehold.co/300x300?text=2',
                'https://placehold.co/300x300?text=3',
                'https://placehold.co/300x300?text=4',
            ]
        ];

        return view('home', $data);
    }
}
