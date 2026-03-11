<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CatalogRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{
    protected CatalogRepository $catalogRepository;

    public function __construct(CatalogRepository $catalogRepository)
    {
        $this->catalogRepository = $catalogRepository;
    }

    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter tab
        $categories = Category::where('type', 'catalog')->get();

        // 1. Buat query builder dari repository
        $query = $this->catalogRepository->query()->with('hasCategory');

        // 2. Filter Kategori
        if ($request->has('kategori') && $request->kategori != 'all') {
            $selectedCategory = $categories->where('slug', $request->kategori)->first();
            if ($selectedCategory) {
                $query->where('id_category', $selectedCategory->id);
            }
        }

        // 3. Filter Pencarian
        if ($request->has('search') && $request->search != null) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 4. Lakukan paginasi
        $paginatedCatalogs = $query->paginate(6)->appends($request->all());

        // 5. Transformasi data untuk view, agar sesuai dengan format yang diharapkan
        $paginatedCatalogs->getCollection()->transform(function ($catalog) {
            // Bangun URL gambar yang aman (handle null dan fallback)
            $imageUrl = null;
            if ($catalog->image) {
                // Jika kolom path diisi (misal "storage/catalog"), gunakan sebagai base path relatif
                if (!empty($catalog->path)) {
                    // Jika path tersimpan sebagai URL penuh, jangan dibungkus asset() lagi
                    if (str_starts_with($catalog->path, 'http://') || str_starts_with($catalog->path, 'https://')) {
                        $imageUrl = rtrim($catalog->path, '/') . '/' . $catalog->image;
                    } else {
                        $imageUrl = asset($catalog->path . '/' . $catalog->image);
                    }
                } else {
                    // Fallback ke lokasi lama jika ada image tapi path kosong
                    $imageUrl = asset('images/catalog/' . $catalog->image);
                }
            } else {
                // Fallback image jika belum ada gambar
                $imageUrl = asset('images/local/logo-tefa.png');
            }

            return [
                'nama' => $catalog->title,
                'slug' => $catalog->slug,
                'kategori' => optional($catalog->hasCategory)->name ?? 'Lainnya',
                'image' => $imageUrl,
                'deskripsi' => $catalog->desc,
                'harga' => 'Hubungi Admin', // Kolom harga belum ada di DB
                'type' => 'Produk/Jasa', // Kolom tipe belum ada di DB
            ];
        });

        return view('products.index', ['products' => $paginatedCatalogs, 'categories' => $categories]);
    }

    public function show($slug)
    {
        // 1. Cari produk berdasarkan slug dari repository
        $productModel = $this->catalogRepository->query()->where('slug', $slug)->first();

        // 2. Jika tidak ketemu, tampilkan 404
        if (!$productModel) {
            abort(404);
        }

        // Format nomor WhatsApp
        $whatsapp = $productModel->whatsapp;
        if ($whatsapp) {
            $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
            if (substr($whatsapp, 0, 1) === '0') {
                $whatsapp = '62' . substr($whatsapp, 1);
            }
        } else {
            $whatsapp = '6283820655083'; // Default jika kosong
        }

        // 3. Mapping data ke format yang diharapkan oleh view
        // Siapkan URL gambar yang aman (handle null dan fallback)
        $imageUrl = null;
        if ($productModel->image) {
            if (!empty($productModel->path)) {
                if (str_starts_with($productModel->path, 'http://') || str_starts_with($productModel->path, 'https://')) {
                    $imageUrl = rtrim($productModel->path, '/') . '/' . $productModel->image;
                } else {
                    $imageUrl = asset($productModel->path . '/' . $productModel->image);
                }
            } else {
                $imageUrl = asset('images/catalog/' . $productModel->image);
            }
        } else {
            $imageUrl = asset('images/local/logo-tefa.png');
        }

        $product = [
            'nama' => $productModel->title,
            'slug' => $productModel->slug,
            'kategori' => optional($productModel->hasCategory)->name ?? 'Lainnya',
            'image' => $imageUrl,
            'deskripsi' => $productModel->desc,
            'deskripsi_lengkap' => '<p>' . $productModel->desc . '</p><p>Hubungi tim TEFA kami untuk konsultasi lebih lanjut mengenai layanan atau produk ini.</p>',
            'spesifikasi' => $productModel->specification ? array_map('trim', explode(',', $productModel->specification)) : ['Kualitas Standar Industri', 'Dikerjakan oleh Teknisi Kompeten', 'Garansi Layanan', 'Konsultasi Gratis'],
            'status' => 'Tersedia',
            'deskripsi_pendek' => $productModel->desc,
            'harga' => 'Hubungi Admin',
            'whatsapp' => $whatsapp,
        ];

        // 4. Ambil Produk Lainnya (Related) - Kecuali produk yang sedang dibuka
        $related = $this->catalogRepository->query()->where('slug', '!=', $slug)->take(6)->get()->map(function ($item) {
            $relatedImageUrl = null;
            if ($item->image) {
                if (!empty($item->path)) {
                    if (str_starts_with($item->path, 'http://') || str_starts_with($item->path, 'https://')) {
                        $relatedImageUrl = rtrim($item->path, '/') . '/' . $item->image;
                    } else {
                        $relatedImageUrl = asset($item->path . '/' . $item->image);
                    }
                } else {
                    $relatedImageUrl = asset('images/catalog/' . $item->image);
                }
            } else {
                $relatedImageUrl = asset('images/local/logo-tefa.png');
            }

            return [
                'nama' => $item->title,
                'slug' => $item->slug,
                'kategori' => optional($item->hasCategory)->name ?? 'Lainnya',
                'image' => $relatedImageUrl,
                'deskripsi' => $item->desc,
                'harga' => 'Hubungi Admin',
            ];
        });

        return view('products.show', compact('product', 'related'));
    }
}
