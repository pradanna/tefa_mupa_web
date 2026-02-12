<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Commons\Controller\BaseController;
use App\Repositories\CategoryRepository;

class CategoryController extends BaseController
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function index()
    {
        try {
            $request = request();
            $type = $request->query('type', 'catalog');

            $query = $this->categoryRepository->query()->where('type', $type)->orderBy('created_at', 'desc');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            $categorys = $query->paginate($request->query('limit', 10));
            return $this->makeView('backoffice.pages.category.index', compact('categorys'));
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->makeView('backoffice.pages.category.index', ['categorys' => []]);
        }
    }

    public function create()
    {
        $parentCategories = [];
        // If creating a sub-category, fetch the parent categories (which are of type 'catalog')
        if (request('type') === 'sub_catalog') {
            $parentCategories = $this->categoryRepository->query()->where('type', 'catalog')->get();
        }

        return view('backoffice.pages.category.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        try {
            $slugInput = $request->input('slug');
            $slug = $slugInput ? \Illuminate\Support\Str::slug($slugInput) : \Illuminate\Support\Str::slug($request->input('name'));

            $formattedData = $request->all();
            $formattedData['slug'] = $slug;

            $schema = new \App\Schemas\CategorySchema();
            $schema->hydrateSchemaBody($formattedData);
            $schema->validate();
            $schema->hydrate();

            $this->categoryRepository->store($schema);

            return redirect()->route('categories.index')->with('success', 'Category created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            // Tangani semua error (termasuk jika method 'createNews' tidak ada) dengan redirect dan pesan error.
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        try {
            $category = $this->categoryRepository->show($id);
            if (!$category || $category instanceof \Throwable) {
                return redirect()->back()->with('error', 'Category Not Found');
            }

            $parentCategories = [];
            // If editing a sub-category, fetch the parent categories (which are of type 'catalog')
            if ($category->type === 'sub_catalog') {
                $parentCategories = $this->categoryRepository->query()->where('type', 'catalog')->get();
            }

            return view('backoffice.pages.category.edit', compact('category', 'parentCategories'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to retrieve category: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $find_category = $this->categoryRepository->show($id);
            if (!$find_category || $find_category instanceof \Throwable) {
                return redirect()->back()->with('error', 'Category not found');
            }

            $category = $this->categoryRepository->update($id, $request);
            return redirect()->route('categories.index')->with('success', 'Category update success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Category Update failed: ' . $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $find_category = $this->categoryRepository->show($id);
            if (!$find_category || $find_category instanceof \Throwable) {
                return redirect()->back()->with('error', 'Category not found');
            }

            $this->categoryRepository->delete($id);

            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete category: ' . $th->getMessage());
        }
    }
}
