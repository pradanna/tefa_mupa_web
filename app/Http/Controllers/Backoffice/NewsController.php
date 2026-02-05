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

    public function __construct( protected NewsRepository $newsRepository,protected CategoryRepository $categoryRepository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $news = $this->newsRepository->findAllWithRelation(request());
            return view('backoffice.pages.news.index',compact('news'));
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
            $categories = $this->categoryRepository->getAll();
            return view('backoffice.pages.news.create', compact('categories'));
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->with('error', 'Failed to load create news form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $schema = new \App\Schemas\NewsSchema();
            $schema->hydrateSchemaBody($request->all());
            $schema->validate();

            if(!$request->hasFile('file')){
                return redirect()->back()->with('error', 'Image is required')->withInput();
            }

            $image = $request->file('file');
            $extension = $image->getClientOriginalExtension();
            $name = now()->format('YmdHis') . '.' . $extension;
            $image->storeAs('images/news', $name, 'public');
            $pathUrl = asset('storage/images/news');

            // Data untuk field pada model News (see app/Models/News.php)
            $requestData = $request->all();
            $requestData['image'] = $name;
            $requestData['path'] = $pathUrl;
            $requestData['id_user'] = \Illuminate\Support\Facades\Auth::user()->id;

            $schema->hydrateSchemaBody($requestData);
            $schema->validate();
            $schema->hydrate();

            $this->newsRepository->createNews($schema);

            return redirect()->route('articles.index')->with('success', 'News created successfully');
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
            $news = $this->newsRepository->show($id);
            if (!$news || $news instanceof \Throwable) {
                return redirect()->back()->with('error', 'News Not Found');
            }
            $categories = $this->categoryRepository->getAll();
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
                $request->file('file')->storeAs('images/news', $newImageName, 'public');
                $newImagePath = asset('storage/images/news');
                $data['image'] = $newImageName;
                $data['path']  = $newImagePath;
            }

            $schema = new \App\Schemas\NewsSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($newImageName) {
                    $filePath = 'images/news/' . $newImageName;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
                    }
                }
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            $schema->hydrate();

            // Setelah validasi, baru hapus gambar lama kalau ada upload gambar baru
            if ($newImageName) {
                $oldFilePath = 'images/news/' . $news->image;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldFilePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFilePath);
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
            return redirect()->route('articles.index')->with('success', 'News updated successfully');

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
                $filePath = 'images/news/' . $news->image;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
                }
            }

            $this->newsRepository->delete($id);
            return redirect()->route('articles.index')->with('success', 'News deleted successfully');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->with('error', 'Failed to delete news: ' . $th->getMessage());
        }
    }
}
