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
                // SLIDE 1: VISI UTAMA (General Branding)
                [
                    'title' => 'Inovasi Tanpa Batas',
                    'subtitle' => 'Mencetak generasi unggul yang siap bersaing di era teknologi 4.0.',
                    'image' => 'images/slider/slider1.jpg'
                ],

                // SLIDE 2: FOKUS PRODUK (Hardware & IoT)
                [
                    'title' => 'Produk Teknologi Terapan',
                    'subtitle' => 'Menghadirkan karya siswa berkompeten.',
                    'image' => 'images/slider/slider2.jpg'
                ],

                // SLIDE 3: FOKUS JASA (Servis & Desain)
                [
                    'title' => 'Layanan Jasa Profesional',
                    'subtitle' => 'Percayakan perawatan kendaraan dan desain arsitektur pada kami.',
                    'image' => 'images/slider/slider3.jpg'
                ],

                // SLIDE 4: KUALITAS & KEPERCAYAAN (Trust)
                [
                    'title' => 'Mitra Terpercaya Industri',
                    'subtitle' => 'Sinergi pendidikan vokasi dan dunia industri untuk kualitas yang teruji.',
                    'image' => 'images/slider/slider4.jpg'
                ],
            ],
            'profil' => [
                'title' => 'Tentang TEFA Mupa',
                'description' => 'Teaching Factory SMK Muhammadiyah Pakem adalah pusat pengembangan kompetensi siswa berbasis industri. Kami menghadirkan produk teknologi tepat guna dan layanan profesional.',
                'image' => 'https://placehold.co/600x400/gray/white?text=Foto+Gedung',
            ],
            // Data dipisah agar mudah dimapping di Tabs
            'produk' => [
                [
                    'nama' => 'Smart RFID Lock',
                    'slug' => 'smart-rfid-lock', // Slug added
                    'kategori' => 'IoT',
                    'img' => 'images/products/rfid.webp'
                ],
            ],
            'jasa' => [
                [
                    'nama' => 'Servis Motor',
                    'slug' => 'servis-motor',
                    'kategori' => 'Teknisi',
                    'img' => 'images/products/service-motor.jpg',
                    'deskripsi' => 'Layanan perawatan berkala, tune-up, dan perbaikan mesin sepeda motor berbagai merek dengan standar industri.'
                ],
                [
                    'nama' => 'Servis Mobil',
                    'slug' => 'servis-mobil',
                    'kategori' => 'Teknisi',
                    'img' => 'images/products/service-mobil.jpg',
                    'deskripsi' => 'Perbaikan umum, ganti oli, service kaki-kaki, hingga engine tune-up untuk performa kendaraan roda empat yang prima.'
                ],
                [
                    'nama' => 'Desain Arsitektur',
                    'slug' => 'desain-arsitektur',
                    'kategori' => 'Desain',
                    'img' => 'images/products/arsitek.jpg',
                    'deskripsi' => 'Jasa rancang bangun hunian dan gedung komersial, mencakup denah 2D, visualisasi 3D, hingga perhitungan RAB.'
                ],
                [
                    'nama' => 'Pembuatan Animasi',
                    'slug' => 'animasi',
                    'kategori' => 'Desain',
                    'img' => 'images/products/animasi.jpg',
                    'deskripsi' => 'Produksi video animasi 2D/3D, motion graphic untuk iklan, dan media pembelajaran interaktif yang kreatif.'
                ],
            ],
            'berita' => [
                ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
                ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
                ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],
            ],
            'gallery' => [
                'images/gallery/11.jpg',
                'images/gallery/2.jpg',
                'images/gallery/3.jpg',
                'images/gallery/4.jpg',
                'images/gallery/5.jpg',
                'images/gallery/6.jpg',
                'images/gallery/7.jpg',
                'images/gallery/8.jpg',

            ]
        ];

        return view('home', $data);
    }
}
