<?php

namespace App\Http\Controllers\backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\NewsRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;

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
            dd($th);
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

            $validator = $schema->validate();

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

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
            $schema->hydrateBody();

            $this->newsRepository->createNews($schema);

            return redirect()->route('backoffice.news.index')->with('success', 'News created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create news: ' . $th->getMessage());
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
        try {
            $news = $this->newsRepository->show($id);
            if (!$news || $news instanceof \Throwable) {
                return redirect()->back()->with('error', 'News not found');
            }
            if(!$request->hasFile('file')){
                return redirect()->back()->with('error', 'Image is required')->withInput();
            }

            $schema = new \App\Schemas\NewsSchema();
            $schema->hydrateSchemaBody($request->all());



        } catch (\Throwable $th) {
            //throw $th;
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
            $this->newsRepository->delete($id);
            return redirect()->route('backoffice.news.index')->with('success', 'News deleted successfully');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->with('error', 'Failed to delete news: ' . $th->getMessage());
        }
    }
}
