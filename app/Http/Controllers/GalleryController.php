<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index()
    {
        // Simulasi Data (Idealnya dari Database)
        // Pastikan file gambarnya ada yang persegi, ada yang memanjang ke bawah
        $gallery = [
            ['src' => 'images/gallery/1.jpg', 'title' => 'Kegiatan Praktek', 'category' => 'Kegiatan'],
            ['src' => 'images/gallery/2.jpg', 'title' => 'Mesin CNC Baru', 'category' => 'Fasilitas'],
            ['src' => 'images/gallery/3.jpg', 'title' => 'Kunjungan Industri', 'category' => 'Kegiatan'],
            ['src' => 'images/gallery/4.jpg', 'title' => 'Produk Smart Home', 'category' => 'Karya'],
            ['src' => 'images/gallery/5.jpg', 'title' => 'Suasana Bengkel', 'category' => 'Fasilitas'],
            ['src' => 'images/gallery/7.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            ['src' => 'images/gallery/8.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            ['src' => 'images/gallery/9.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            ['src' => 'images/gallery/10.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            ['src' => 'images/gallery/11.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            ['src' => 'images/gallery/12.jpg', 'title' => 'Juara Lomba', 'category' => 'Prestasi'],
            // ... tambah data lain
        ];

        return view('gallery.index', compact('gallery'));
    }
}
