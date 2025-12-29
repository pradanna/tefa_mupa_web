<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // 1. DATA DUMMY (Nanti diganti database)
        $data = [
            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],
            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

            ['judul' => 'Kunjungan Industri 2025', 'slug' => 'kunjungan-industri-panasonic', 'tanggal' => '2025-12-24', 'excerpt' => 'Siswa melakukan kunjungan ke pabrik elektronik terkemuka...', 'img' => 'images/articles/kunjungan-industri.png'],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],



        ];

        $articles = collect($data);

        // Filter by Judul (Search)
        if ($request->has('search') && $request->search != null) {
            $articles = $articles->filter(function ($item) use ($request) {
                return false !== stripos($item['judul'], $request->search);
            });
        }

        // Filter by Tanggal
        if ($request->has('date') && $request->date != null) {
            $articles = $articles->where('tanggal', $request->date);
        }

        // Jika nanti pakai Database, cukup: Article::paginate(6);
        $perPage = 6;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $articles->forPage($page, $perPage);

        $paginatedArticles = new LengthAwarePaginator(
            $items,
            $articles->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        // Append query string agar saat ganti halaman, filternya tidak hilang
        $paginatedArticles->appends($request->all());

        return view('news.index', ['articles' => $paginatedArticles]);
    }

    public function show($slug)
    {
        $allData = collect([
            [
                'judul' => 'Kunjungan Industri ke PT Panasonic',
                'slug' => 'kunjungan-industri-panasonic',
                'tanggal' => '2025-12-24',
                'kategori' => 'Kegiatan',
                'img' => 'images/articles/kunjungan-industri.png',
                'content' => 'Lorem ipsum dolor sit amet...'
            ],
            ['judul' => 'Juara 1 Lomba Robotik', 'slug' => 'juara-robotik', 'tanggal' => '2025-12-20', 'kategori' => 'Prestasi', 'excerpt' => 'Tim robotik sekolah berhasil menyabet emas...', 'img' => 'images/articles/robotik.jpg'],
            ['judul' => 'Workshop IoT Gratis',  'slug' => 'workshop-iot', 'tanggal' => '2025-11-15', 'kategori' => 'Kegiatan', 'excerpt' => 'Membuka wawasan masyarakat tentang teknologi...', 'img' => 'images/articles/wshop.png'],

        ]);

        $article = $allData->firstWhere('slug', $slug);

        // Jika tidak ketemu, tampilkan 404
        if (!$article) {
            abort(404);
        }

        $related = $allData->where('slug', '!=', $slug)->take(4);

        return view('news.show', compact('article', 'related'));
    }
}
