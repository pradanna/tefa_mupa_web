<?php

namespace App\Http\Controllers;

use App\Repositories\SliderRepository;
use App\Repositories\PatnersRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\NewsRepository;
use App\Repositories\GalleriRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct(
        protected SliderRepository $sliderRepository,
        protected PatnersRepository $patnersRepository,
        protected HistoryRepository $historyRepository,
        protected CategoryRepository $categoryRepository,
        protected CatalogRepository $catalogRepository,
        protected NewsRepository $newsRepository,
        protected GalleriRepository $galleriRepository,
    ) {
        // Tidak perlu memanggil parent::__construct() karena Controller dasar Laravel tidak punya konstruktor
    }
    public function index()
    {

        // Ambil data mitra dari database via PatnersRepository
        $partnerRecords = $this->patnersRepository->getAll();
        $pathUrl = asset('images/partners');
        $partners = $partnerRecords->map(function ($partner) use ($pathUrl) {
            return [
                'name' => $partner->name,
                // path url penuh: base path + nama file
                'img' => $pathUrl . '/' . $partner->image,
            ];
        })->toArray();

        // Ambil data slider dari database via SliderRepository
        $sliders = $this->sliderRepository->getAll();
        $hero = $sliders->map(function ($slider) {
            return [
                'title' => $slider->title,
                'subtitle' => $slider->subtitle,
                // path di-backoffice sudah berupa URL penuh, aman dipakai langsung di view (dibungkus asset() tidak akan double base-url)
                'image' => $slider->path . '/' . $slider->file,
            ];
        })->toArray();

        // Ambil data profil (history) dari HistoryRepository
        $history = $this->historyRepository->findFirst();
        $profil = [
            'title' => $history?->title ?? ('Tentang ' . config('app.short_name')),
            'description' => $history?->body ?? (config('app.full_name_uppercase') . ' adalah pusat pengembangan kompetensi siswa berbasis industri. Kami menghadirkan produk teknologi tepat guna dan layanan profesional.'),
            'image' => $history && $history->image
                ? ($history->path . '/' . $history->image)
                : 'https://placehold.co/600x400/gray/white?text=Foto+Gedung',
        ];
        // Data Produk diambil dari CatalogRepository
        $catalogs = $this->catalogRepository->getCategoryCataloge();
        $produk = $catalogs->map(function ($catalog) {
            $slug = $catalog->slug ?? $catalog->id;
            $imagePath = $catalog->path
                ? $catalog->path . '/' . $catalog->image
                : $catalog->image;

            return [
                'nama' => $catalog->title,
                'path' => $catalog->path,
                'slug' => $slug,
                'link' => route('products.show', $slug),
                'kategori' => optional($catalog->hasCategory)->name ?? 'Lainnya',
                'category_id' => $catalog->id_category,
                'image' => $imagePath,
                'deskripsi' => $catalog->desc,
            ];
        })->toArray();

        // Data kategori untuk filter (hanya type = catalog)
        $kategoti = $this->categoryRepository->getCategoryCataloge();

        // Ambil 3 berita terbaru dari NewsRepository
        $latestNews = $this->newsRepository->getLatestNews(3);
        $berita = $latestNews->map(function ($news) {
            $imagePath = $news->path
                ? $news->path . '/' . $news->image
                : $news->image;

            return [
                'judul' => $news->title,
                'slug' => $news->slug,
                'tanggal' => $news->date,
                // gunakan konten penuh sebagai sumber excerpt, dibatasi lagi di component
                'excerpt' => $news->content,
                'img' => $imagePath,
            ];
        })->toArray();
        // Ambil data galeri dari GalleriRepository
        $galleries = $this->galleriRepository->getAll();
        $gallery = $galleries->map(function ($item) {
            // path di DB sudah menyimpan base URL folder, tinggal gabung dengan nama file
            $imagePath = $item->path
                ? $item->path . '/' . $item->image
                : $item->image;
            return $imagePath;
        })->toArray();

        return view('home', compact('hero', 'profil', 'produk', 'berita', 'gallery',  'partners', 'kategoti'));
    }
}
