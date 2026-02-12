<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Schemas\GallerSchema;
use App\Repositories\GalleriRepository;
use App\Commons\Controller\BaseController;
use Illuminate\Support\Facades\Log;

class GalleriController extends BaseController
{



    public function __construct(
        protected GalleriRepository $galeriRepository
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $gallerys = $this->galeriRepository->paginate(request());
            return view('backoffice.pages.galleri.index', compact('gallerys'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->withErrors(['message' => 'An error occurred. Please try again.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('backoffice.pages.galleri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $gallery = $this->galeriRepository->show($id);
            if (!$gallery || $gallery instanceof \Throwable) {
                return redirect()->back()->with('error', 'image not found');
            }
            $imageDelete = $this->galeriRepository->delete($id);
            if ($imageDelete) {
                $filePath = 'images/galleri/' . $gallery->image;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
                }
            }
            return redirect()->route('album.index')->with('success', 'Gambar berhasil dihapus');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->with('error', 'Failed to delete image: ' . $th->getMessage());
        }
    }
}
