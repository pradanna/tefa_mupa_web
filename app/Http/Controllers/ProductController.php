<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    /**
     * Sumber data tunggal (Single Source of Truth)
     * Disimpan di private function agar bisa dipanggil oleh index() dan show()
     */
    private function getData()
    {
        return collect([
            [
                'nama' => 'Smart RFID Lock',
                'tipe' => 'Produk',
                'slug' => 'smart-rfid-lock',
                'kategori' => 'IoT',
                'img' => 'images/products/rfid.webp',
                'harga' => 'Rp 350.000', // Saya tambahkan estimasi harga
                'deskripsi' => 'Sistem keamanan pintu otomatis berbasis kartu RFID, solusi praktis dan aman untuk laboratorium atau perkantoran.'
            ],
            [
                'nama' => 'Servis Motor',
                'tipe' => 'Jasa',
                'slug' => 'servis-motor',
                'kategori' => 'Teknisi',
                'img' => 'images/products/service-motor.jpg',
                'harga' => 'Mulai Rp 45.000',
                'deskripsi' => 'Layanan perawatan berkala, tune-up, dan perbaikan mesin sepeda motor berbagai merek dengan standar industri.'
            ],
            [
                'nama' => 'Servis Mobil',
                'tipe' => 'Jasa',
                'slug' => 'servis-mobil',
                'kategori' => 'Teknisi',
                'img' => 'images/products/service-mobil.jpg',
                'harga' => 'Mulai Rp 150.000',
                'deskripsi' => 'Perbaikan umum, ganti oli, service kaki-kaki, hingga engine tune-up untuk performa kendaraan roda empat yang prima.'
            ],
            [
                'nama' => 'Desain Arsitektur',
                'tipe' => 'Jasa',
                'slug' => 'desain-arsitektur',
                'kategori' => 'Desain',
                'img' => 'images/products/arsitek.jpg',
                'harga' => 'Hubungi Admin',
                'deskripsi' => 'Jasa rancang bangun hunian dan gedung komersial, mencakup denah 2D, visualisasi 3D, hingga perhitungan RAB.'
            ],
            [
                'nama' => 'Pembuatan Animasi',
                'tipe' => 'Jasa',
                'slug' => 'animasi',
                'kategori' => 'Desain',
                'img' => 'images/products/animasi.jpg',
                'harga' => 'Hubungi Admin',
                'deskripsi' => 'Produksi video animasi 2D/3D, motion graphic untuk iklan, dan media pembelajaran interaktif yang kreatif.'
            ],
        ]);
    }

    public function index(Request $request)
    {
        // 1. Ambil data dari fungsi privat di atas
        $products = $this->getData();

        // 2. Filter Kategori (Produk / Jasa) dari Tab
        if ($request->has('kategori') && $request->kategori != 'all') {
            $products = $products->where('tipe', $request->kategori);
        }

        // 3. Search Filter (Opsional, jika ada pencarian nama)
        if ($request->has('search') && $request->search != null) {
            $products = $products->filter(function ($item) use ($request) {
                return false !== stripos($item['nama'], $request->search);
            });
        }

        // 4. Pagination Manual (Karena pakai Array, bukan Database)
        $perPage = 6; // Jumlah item per halaman
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $products->forPage($page, $perPage);

        $paginatedProducts = new LengthAwarePaginator(
            $items,
            $products->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        // Append query string agar filter tidak hilang saat ganti halaman
        $paginatedProducts->appends($request->all());

        return view('products.index', ['products' => $paginatedProducts]);
    }

    public function show($slug)
    {
        // 1. Cari produk berdasarkan slug dari data yang sama
        $product = $this->getData()->firstWhere('slug', $slug);

        // 2. Jika tidak ketemu, tampilkan 404
        if (!$product) {
            abort(404);
        }

        // 3. Tambahkan data dummy detail tambahan (Spesifikasi & Deskripsi Lengkap)
        // Ini agar halaman detail tidak error saat memanggil key ini
        $product['deskripsi_lengkap'] = $product['deskripsi_lengkap'] ?? '<p>' . $product['deskripsi'] . '</p><p>Hubungi tim TEFA kami untuk konsultasi lebih lanjut mengenai layanan atau produk ini.</p>';

        $product['spesifikasi'] = $product['spesifikasi'] ?? [
            'Kualitas Standar Industri',
            'Dikerjakan oleh Teknisi Kompeten',
            'Garansi Layanan',
            'Konsultasi Gratis'
        ];

        $product['status'] = 'Tersedia'; // Default status
        $product['deskripsi_pendek'] = $product['deskripsi']; // Mapping deskripsi pendek

        // 4. Ambil Produk Lainnya (Related) - Kecuali produk yang sedang dibuka
        $related = $this->getData()->where('slug', '!=', $slug)->take(3);

        return view('products.show', compact('product', 'related'));
    }
}
