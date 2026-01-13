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
use App\Schemas\CatalogSchema;
class CatalogController extends BaseController
{

    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected CatalogRepository $catalogRepository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $catalogs = $this->catalogRepository->paginate(request());
            return view('backoffice.pages.catalog.index',compact('catalogs'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error',$th);
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
            return view('backoffice.pages.catalog.create',compact('categoryProducts','subCategorys'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error',$th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $payload = [
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title')),
                'id_category' => $request->input('id_category'),
                'id_sub_category' => $request->input('id_sub_category'),
                'content' => $request->input('content'),
                'date' => $request->input('date'),
                'status' => $request->input('status'),
                'id_user' => Auth::user()->id,
            ];
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $name      = Datetime::getNowYmdHis();
                $fileName  = $name .'.'. $extension;
                $file->storeAs('images/catalog',$fileName,'public');
                $path = asset('storage/images/catalog');
                $payload['path'] = $path;
                $payload['image'] = $fileName;
            };

            $schema = new CatalogSchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();

            $data = $this->catalogRepository->store($payload);

            return redirect()->route('catalog.index')->with('message','success create catalog');

        } catch (\Exception $e) {
            throw $e;
        }
        catch (\Throwable $th) {
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

            return view('backoffice.pages.catalog.edit',compact('catalog','categoryProducts','subCategorys'));
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

            $payload = [
                'title' => $request->input('title', $catalog->title),
                'id_category' => $request->input('id_category', $catalog->id_category),
                'id_sub_category' => $request->input('id_sub_category', $catalog->id_sub_category),
                'desc' => $request->input('desc', $catalog->desc),
                'image' => $catalog->image,
                'path' => $catalog->path,
                'id_user' => Auth::user()->id,
            ];

            $newImageName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $name = Datetime::getNowYmdHis();
                $newImageName = $name . '.' . $extension;
                $file->storeAs('images/catalog', $newImageName, 'public');
                $payload['image'] = $newImageName;
                $payload['path'] = asset('storage/images/catalog');
            }

            $schema = new CatalogSchema();
            $schema->hydrateSchemaBody($payload);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($newImageName) {
                    $filePath = 'images/catalog/' . $newImageName;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
                    }
                }
                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            $schema->hydrate();

            // Hapus gambar lama jika ada upload gambar baru
            if ($newImageName && $catalog->image) {
                $oldFilePath = 'images/catalog/' . $catalog->image;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldFilePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFilePath);
                }
            }

            $updateData = [
                'title' => $schema->getTitle(),
                'id_category' => $schema->getIdCategory(),
                'id_sub_category' => $schema->getIdSubCategory(),
                'desc' => $schema->getDesc(),
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
                $filePath = 'images/catalog/' . $catalog->image;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
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
