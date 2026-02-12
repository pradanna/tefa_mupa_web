<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\NewsRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;;

class NewsController extends BaseController
{

    public function __construct(protected NewsRepository $newsRepository, protected CategoryRepository $categoryRepository) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $news = $this->newsRepository->findAllWithRelation(request());
            return view('backoffice.pages.news.index', compact('news'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to retrieve news data.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Fetch only categories with type 'content' for news articles.
            $categories = $this->categoryRepository->getCategoryNews();
            return view('backoffice.pages.news.create', compact('categories'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('articles.index')->with('error', 'Gagal memuat halaman tambah berita.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $imageName = null;
        try {
            // 1. Validasi file terlebih dahulu
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Gambar berita wajib diunggah.')->withInput();
            }

            // 2. Proses upload file
            $image = $request->file('file');
            $extension = $image->getClientOriginalExtension();
            $imageName = now()->format('YmdHis') . '.' . $extension;
            $destinationPath = public_path('images/news');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $pathUrl = asset('images/news');

            // 3. Siapkan data lengkap untuk validasi
            $payload = $request->all();
            $payload['image'] = $imageName;
            $payload['path'] = $pathUrl;
            $payload['id_user'] = \Illuminate\Support\Facades\Auth::id();
            if (empty($payload['slug'])) {
                $payload['slug'] = \Illuminate\Support\Str::slug($payload['title']);
            }

            // 4. Validasi dan hydrate schema
            $schema = new \App\Schemas\NewsSchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();
            $schema->hydrate();

            // 5. Simpan ke database
            $this->newsRepository->createNews($schema);

            return redirect()->route('articles.index')->with('success', 'Berita berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, hapus file yang sudah di-upload
            if ($imageName) {
                $filePath = public_path('images/news/' . $imageName);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            return redirect()->back()->withErrors($e->errors())->withInput($request->except('file'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('file'))
                ->with('error', 'Terjadi kesalahan sistem saat membuat berita.');
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
            $news = $this->newsRepository->show($id);
            if (!$news || $news instanceof \Throwable) {
                return redirect()->back()->with('error', 'News Not Found');
            }
            // Fetch only categories with type 'content' for news articles.
            $categories = $this->categoryRepository->getCategoryNews();
            return view('backoffice.pages.news.edit', compact('news', 'categories'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to retrieve news: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $news = $this->newsRepository->show($id);
            if (!$news || $news instanceof \Throwable) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'News not found');
            }

            $data = [
                'id'           => $news->id,
                'title'        => $request->input('title', $news->title),
                'slug'         => $request->input('slug', $news->slug),
                'id_category'  => $request->input('id_category', $news->id_category),
                'image'        => $news->image,
                'path'         => $news->path,
                'content'      => $request->input('content', $news->content),
                'date'         => $request->input('date', $news->date),
                'status'       => $request->input('status', $news->status),
                'id_user'      => \Illuminate\Support\Facades\Auth::user()->id,
            ];

            $newImageName = null;
            $newImagePath = null;
            if ($request->hasFile('file')) {
                $extension = $request->file('file')->getClientOriginalExtension();
                $newImageName = now()->format('YmdHis') . '.' . $extension;
                $destinationPath = public_path('images/news');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $request->file('file')->move($destinationPath, $newImageName);
                $newImagePath = asset('images/news');
                $data['image'] = $newImageName;
                $data['path']  = $newImagePath;
            }

            $schema = new \App\Schemas\NewsSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($newImageName) {
                    $filePath = public_path('images/news/' . $newImageName);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            $schema->hydrate();

            // Setelah validasi, baru hapus gambar lama kalau ada upload gambar baru
            if ($newImageName) {
                $oldFilePath = public_path('images/news/' . $news->image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $updateData = [
                'title'        => $schema->getTitle(),
                'slug'         => $schema->getSlug(),
                'id_category'  => $schema->getIdCategory(),
                'image'        => $schema->getImage(),
                'path'         => $schema->getPath(),
                'content'      => $schema->getContent(),
                'date'         => $schema->getDate(),
                'status'       => $schema->getStatus(),
                'id_user'      => $schema->getIdUser(),
            ];

            $this->newsRepository->update($id, $updateData);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('articles.index')->with('success', 'Berita berhasil diperbarui');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update news: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $news = $this->newsRepository->show($id);
            if (!$news || $news instanceof \Throwable) {
                return redirect()->back()->with('error', 'News not found');
            }

            // Hapus file gambar jika ada
            if (!empty($news->image)) {
                $filePath = public_path('images/news/' . $news->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->newsRepository->delete($id);
            return redirect()->route('articles.index')->with('success', 'Berita berhasil dihapus');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->with('error', 'Failed to delete news: ' . $th->getMessage());
        }
    }
}
