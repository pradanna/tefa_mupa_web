<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\CatalogRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Commons\Libs\Datetime;
use Illuminate\Support\Facades\Storage;
use App\Schemas\CatalogSchema;

class CatalogController extends BaseController
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected CatalogRepository $catalogRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $catalogs = $this->catalogRepository->paginate(request());
            return view('backoffice.pages.catalog.index', compact('catalogs'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categoryProducts = $this->categoryRepository->getCategoryCataloge();
            $subCategorys     = $this->categoryRepository->getSubCategoryCataloge();
            return view('backoffice.pages.catalog.create', compact('categoryProducts', 'subCategorys'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', $th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi dasar termasuk file gambar
            $request->validate([
                'title'        => ['required', 'string', 'max:255'],
                'id_category'  => ['required', 'integer'],
                'desc'         => ['required', 'string'],
                'file'         => ['required', 'image', 'max:2048'], // ~2MB
            ]);

            $payload = [
                'title' => $request->input('title'),
                'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title')),
                'id_category' => $request->input('id_category'),
                'id_sub_category' => $request->input('id_sub_category'),
                // gunakan field "desc" agar sesuai dengan schema & kolom tabel
                'desc' => $request->input('desc'),
                'specification' => $request->input('specification'),
                'whatsapp' => $request->input('whatsapp'),
                'id_user' => Auth::user()->id,
            ];

            // Pastikan hanya mengakses file ketika benar-benar ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Simpan ke storage/app/public/catalog dan simpan path relatifnya
                $storedPath = $file->store('catalog', 'public'); // contoh: catalog/namafile.jpg

                // Simpan directory relatif dan nama file di database
                $payload['path'] = 'storage/' . dirname($storedPath); // contoh: storage/catalog
                $payload['image'] = basename($storedPath); // contoh: namafile.jpg
            }

            $schema = new CatalogSchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();

            $data = $this->catalogRepository->store($payload);

            return redirect()->route('catalog.index')->with('message', 'success create catalog');
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('file'))
                ->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $catalog = $this->catalogRepository->show($id);
            if (!$catalog || $catalog instanceof \Throwable) {
                return redirect()->back()->with('error', 'Catalog not found');
            }
            $categoryProducts = $this->categoryRepository->getCategoryCataloge();
            $subCategorys     = $this->categoryRepository->getSubCategoryCataloge();

            return view('backoffice.pages.catalog.edit', compact('catalog', 'categoryProducts', 'subCategorys'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $catalog = $this->catalogRepository->show($id);
            if (!$catalog || $catalog instanceof \Throwable) {
                return redirect()->back()->with('error', 'Catalog not found');
            }

            // Validasi dasar; file boleh kosong saat update
            $request->validate([
                'title'        => ['required', 'string', 'max:255'],
                'id_category'  => ['required', 'integer'],
                'desc'         => ['required', 'string'],
                'file'         => ['nullable', 'image', 'max:2048'],
            ]);

            $payload = [
                'title' => $request->input('title', $catalog->title),
                'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title', $catalog->title)),
                'id_category' => $request->input('id_category', $catalog->id_category),
                'id_sub_category' => $request->input('id_sub_category', $catalog->id_sub_category),
                'desc' => $request->input('desc', $catalog->desc),
                'specification' => $request->input('specification', $catalog->specification),
                'whatsapp' => $request->input('whatsapp', $catalog->whatsapp),
                'image' => $catalog->image,
                'path' => $catalog->path,
                'id_user' => Auth::user()->id,
            ];

            $newImageName = null;
            $oldStoragePath = null;
            if ($request->hasFile('file')) {
                // Simpan info gambar lama (jika disimpan di storage publik dengan kolom path)
                if ($catalog->image && $catalog->path && str_starts_with($catalog->path, 'storage/')) {
                    // contoh path: storage/catalog dan image: namafile.jpg
                    $oldStoragePath = str_replace('storage/', '', $catalog->path) . '/' . $catalog->image; // catalog/namafile.jpg
                }

                $file = $request->file('file');

                // Simpan ke storage/app/public/catalog
                $storedPath = $file->store('catalog', 'public'); // contoh: catalog/namafile-baru.jpg
                $newImageName = basename($storedPath);

                $payload['image'] = $newImageName;
                $payload['path'] = 'storage/' . dirname($storedPath); // contoh: storage/catalog
            }

            $schema = new CatalogSchema();
            $schema->hydrateSchemaBody($payload);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Jika upload gambar baru dan validasi gagal, hapus gambar yang tadi di-upload dari storage publik
                if ($newImageName) {
                    $tempPath = 'catalog/' . $newImageName;
                    Storage::disk('public')->delete($tempPath);
                }
                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            $schema->hydrate();

            // Hapus gambar lama jika upload gambar baru DAN path lama diketahui di storage publik
            if ($newImageName && $oldStoragePath) {
                Storage::disk('public')->delete($oldStoragePath);
            }

            $updateData = [
                'title' => $schema->getTitle(),
                'slug' => $schema->getSlug(),
                'id_category' => $schema->getIdCategory(),
                'id_sub_category' => $schema->getIdSubCategory(),
                'desc' => $schema->getDesc(),
                'specification' => $schema->getSpecification(),
                'whatsapp' => $schema->getWhatsapp(),
                'image' => $schema->getImage(),
                'path' => $schema->getPath(),
                'id_user' => $schema->getIdUser(),
            ];

            $this->catalogRepository->update($id, $updateData);

            return redirect()->route('catalog.index')->with('message', 'success update catalog');
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('file'))
                ->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $catalog = $this->catalogRepository->show($id);

            if (!$catalog) {
                return redirect()->route('catalog.index')->with('error', 'Catalog tidak ditemukan.');
            }

            // Hapus file gambar jika ada
            if ($catalog->image) {
                $filePath = public_path('images/catalog/' . $catalog->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->catalogRepository->delete($id);

            return redirect()->route('catalog.index')->with('success', 'Catalog berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('catalog.index')
                ->with('error', 'Terjadi kesalahan saat menghapus catalog.');
        }
    }
}
