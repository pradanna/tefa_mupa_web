<?php

namespace Tests\Feature;

use App\Models\Catalog;
use App\Models\Category;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_utama_berhasil_diakses(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_halaman_profil_berhasil_diakses(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertViewIs('profile');
    }

    public function test_halaman_profil_menampilkan_section_visi_misi(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('id="visi-misi"', false);
    }

    public function test_halaman_profil_menampilkan_section_struktur_organisasi(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('id="struktur-organisasi"', false);
    }

    public function test_halaman_profil_menampilkan_section_sejarah(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('id="sejarah"', false);
    }

    public function test_halaman_daftar_produk_berhasil_diakses(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    }

    public function test_halaman_daftar_produk_dapat_difilter_berdasarkan_kategori(): void
    {
        $catProduk = Category::create(['type' => 'catalog', 'slug' => 'Produk', 'name' => 'Produk']);
        $catJasa = Category::create(['type' => 'catalog', 'slug' => 'Jasa', 'name' => 'Jasa']);
        $subCat = Category::create(['type' => 'sub_catalog', 'slug' => 'iot', 'name' => 'Iot']);

        try {
            $response = $this->get(route('products.index', ['kategori' => 'Produk']));
            $response->assertStatus(200);
            $response->assertViewIs('products.index');
        } finally {
            $catProduk->delete();
            $catJasa->delete();
            $subCat->delete();
        }
    }

    public function test_halaman_daftar_produk_filter_kategori_produk_menampilkan_produk_saja(): void
    {
        $catProduk = Category::create(['type' => 'catalog', 'slug' => 'Produk', 'name' => 'Produk']);
        $catJasa = Category::create(['type' => 'catalog', 'slug' => 'Jasa', 'name' => 'Jasa']);
        $subCat = Category::create(['type' => 'sub_catalog', 'slug' => 'iot', 'name' => 'Iot']);
        $produk1 = Catalog::create([
            'title' => 'Smart RFID Lock',
            'slug' => 'smart-rfid-lock',
            'id_category' => $catProduk->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);
        $produk2 = Catalog::create([
            'title' => 'Servis Motor',
            'slug' => 'servis-motor',
            'id_category' => $catJasa->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img2.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('products.index', ['kategori' => 'Produk']));
            $response->assertStatus(200);
            $response->assertViewIs('products.index');
            $response->assertViewHas('products');
            $response->assertSee('Smart RFID Lock', false);
            $response->assertDontSee('Servis Motor', false);
        } finally {
            $produk1->delete();
            $produk2->delete();
            $catProduk->delete();
            $catJasa->delete();
            $subCat->delete();
        }
    }

    public function test_halaman_daftar_produk_filter_kategori_jasa_menampilkan_jasa_saja(): void
    {
        $catProduk = Category::create(['type' => 'catalog', 'slug' => 'Produk', 'name' => 'Produk']);
        $catJasa = Category::create(['type' => 'catalog', 'slug' => 'Jasa', 'name' => 'Jasa']);
        $subCat = Category::create(['type' => 'sub_catalog', 'slug' => 'iot', 'name' => 'Iot']);
        $produk1 = Catalog::create([
            'title' => 'Smart RFID Lock',
            'slug' => 'smart-rfid-lock',
            'id_category' => $catProduk->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);
        $produk2 = Catalog::create([
            'title' => 'Servis Motor',
            'slug' => 'servis-motor',
            'id_category' => $catJasa->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img2.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('products.index', ['kategori' => 'Jasa']));
            $response->assertStatus(200);
            $response->assertViewIs('products.index');
            $response->assertViewHas('products');
            $response->assertSee('Servis Motor', false);
            $response->assertDontSee('Smart RFID Lock', false);
        } finally {
            $produk1->delete();
            $produk2->delete();
            $catProduk->delete();
            $catJasa->delete();
            $subCat->delete();
        }
    }

    public function test_halaman_daftar_produk_dapat_dicari(): void
    {
        $catProduk = Category::create(['type' => 'catalog', 'slug' => 'Produk', 'name' => 'Produk']);
        $subCat = Category::create(['type' => 'sub_catalog', 'slug' => 'iot', 'name' => 'Iot']);
        $catalog = Catalog::create([
            'title' => 'Produk RFID Pintar',
            'slug' => 'produk-rfid-pintar',
            'id_category' => $catProduk->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('products.index', ['search' => 'RFID']));
            $response->assertStatus(200);
            $response->assertViewIs('products.index');
        } finally {
            $catalog->delete();
            $catProduk->delete();
            $subCat->delete();
        }
    }

    public function test_halaman_detail_produk_dengan_slug_valid_berhasil_diakses(): void
    {
        $catProduk = Category::create(['type' => 'catalog', 'slug' => 'Produk', 'name' => 'Produk']);
        $subCat = Category::create(['type' => 'sub_catalog', 'slug' => 'iot', 'name' => 'Iot']);
        $catalog = Catalog::create([
            'title' => 'Smart RFID Lock',
            'slug' => 'smart-rfid-lock',
            'id_category' => $catProduk->id,
            'id_sub_category' => $subCat->id,
            'image' => 'img.jpg',
            'path' => asset('images/catalog'),
            'desc' => 'Deskripsi',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('products.show', ['slug' => 'smart-rfid-lock']));
            $response->assertStatus(200);
            $response->assertViewIs('products.show');
            $response->assertViewHas('product');
            $response->assertViewHas('related');
        } finally {
            $catalog->delete();
            $catProduk->delete();
            $subCat->delete();
        }
    }

    public function test_halaman_detail_produk_dengan_slug_tidak_valid_menampilkan_404(): void
    {
        $response = $this->get(route('products.show', ['slug' => 'slug-produk-tidak-ada']));

        $response->assertStatus(404);
    }

    public function test_halaman_daftar_berita_berhasil_diakses(): void
    {
        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertViewIs('news.index');
        $response->assertViewHas('articles');
    }

    public function test_halaman_daftar_berita_dapat_dicari(): void
    {
        $catContent = Category::create(['type' => 'content', 'slug' => 'prestasi', 'name' => 'Prestasi']);
        $news = News::create([
            'title' => 'Artikel Robotik di Sekolah',
            'slug' => 'artikel-robotik-di-sekolah',
            'id_category' => $catContent->id,
            'image' => 'img.jpg',
            'path' => asset('images/news'),
            'content' => 'Konten',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('news.index', ['search' => 'Robotik']));
            $response->assertStatus(200);
            $response->assertViewIs('news.index');
        } finally {
            $news->delete();
            $catContent->delete();
        }
    }

    public function test_halaman_detail_berita_dengan_slug_valid_berhasil_diakses(): void
    {
        $catContent = Category::create(['type' => 'content', 'slug' => 'prestasi', 'name' => 'Prestasi']);
        $news = News::create([
            'title' => 'Kunjungan Industri Panasonic',
            'slug' => 'kunjungan-industri-panasonic',
            'id_category' => $catContent->id,
            'image' => 'img.jpg',
            'path' => asset('images/news'),
            'content' => 'Konten berita.',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'id_user' => 1,
        ]);

        try {
            $response = $this->get(route('news.show', ['slug' => 'kunjungan-industri-panasonic']));
            $response->assertStatus(200);
            $response->assertViewIs('news.show');
            $response->assertViewHas('article');
            $response->assertViewHas('related');
        } finally {
            $news->delete();
            $catContent->delete();
        }
    }

    public function test_halaman_detail_berita_dengan_slug_tidak_valid_menampilkan_404(): void
    {
        $response = $this->get(route('news.show', ['slug' => 'slug-berita-tidak-ada']));

        $response->assertStatus(404);
    }

    public function test_halaman_galeri_berhasil_diakses(): void
    {
        $response = $this->get(route('gallery.index'));

        $response->assertStatus(200);
        $response->assertViewIs('gallery.index');
        $response->assertViewHas('gallery');
    }

    public function test_halaman_kontak_berhasil_diakses(): void
    {
        $response = $this->get(route('contact.index'));

        $response->assertStatus(200);
        $response->assertViewIs('contact.index');
    }

    public function test_url_root_menampilkan_halaman_utama(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }
}
