<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected NewsRepository $newsRepository) {}

    public function index(Request $request)
    {
        // 1. Query Builder
        $query = $this->newsRepository->query()->with('category');

        // 2. Filter Search
        if ($request->has('search') && $request->search != null) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 3. Filter Date
        if ($request->has('date') && $request->date != null) {
            $query->whereDate('date', $request->date);
        }

        // 4. Order & Pagination
        $paginatedArticles = $query->where('status', 'publis')
            ->orderBy('date', 'desc')
            ->paginate(6)
            ->appends($request->all());

        // 5. Transform data for view
        $paginatedArticles->getCollection()->transform(function ($item) {
            return [
                'judul' => $item->title,
                'slug' => $item->slug,
                'tanggal' => $item->date,
                'excerpt' => $item->content,
                'img' => $item->path . '/' . $item->image,
            ];
        });

        return view('news.index', ['articles' => $paginatedArticles]);
    }

    public function show($slug)
    {
        // 1. Fetch Article
        $newsModel = $this->newsRepository->query()->where('slug', $slug)->with('category')->first();

        if (!$newsModel || $newsModel->status != 'publis') {
            abort(404);
        }

        // 2. Map Data
        $article = [
            'judul' => $newsModel->title,
            'slug' => $newsModel->slug,
            'tanggal' => \Carbon\Carbon::parse($newsModel->date)->locale('id')->isoFormat('D MMMM Y'),
            'kategori' => optional($newsModel->category)->name ?? 'Umum',
            'img' => $newsModel->path . '/' . $newsModel->image,
            'content' => $newsModel->content,
        ];

        // 3. Fetch Related
        $related = $this->newsRepository->query()
            ->where('slug', '!=', $slug)
            ->where('status', 'publis')
            ->orderBy('date', 'desc')
            ->take(4)
            ->get()
            ->map(function ($item) {
                return [
                    'judul' => $item->title,
                    'slug' => $item->slug,
                    'tanggal' => $item->date,
                    'img' => $item->path . '/' . $item->image,
                    'excerpt' => $item->content,
                ];
            });

        return view('news.show', compact('article', 'related'));
    }
}
