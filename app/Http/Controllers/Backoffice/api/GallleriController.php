<?php

namespace App\Http\Controllers\Backoffice\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\GalleriRepository;

class GallleriController extends BaseController
{
    public function __construct(
        protected GalleriRepository $galeriRepository
    )
    {}

    /**
     * Simpan gambar menggunakan fungsi saveImage di repository
     */
    public function store(Request $request)
    {
        try {
            if($request->hasFile('file')){
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $name  = now()->format('YmdHis');
                $fileName = $name .'.'. $extension;
                $file->storeAs('images/galleri', $fileName, 'public');
                $path = asset('storage/images/galleri');
                $payload = [
                    'image' => $fileName,
                    'path'  => $path,
                ];
                $result = $this->galeriRepository->saveImage($payload);
                return response()->json([
                    'success' => true,
                    'data' => $result
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store image',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
