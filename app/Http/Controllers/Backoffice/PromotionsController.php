<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\PromotionsRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PromotionsController extends BaseController
{

    public function __construct(protected PromotionsRepository $promotionsRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $promotions = $this->promotionsRepository->findAll(request());

            // Pastikan $promotions adalah instance dari LengthAwarePaginator
            if (!$promotions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                throw new \Exception('Invalid pagination result');
            }

            return view('backoffice.pages.promotions.index', compact('promotions'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to retrieve promotions data: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('backoffice.pages.promotions.create');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to load create promotion form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi file terlebih dahulu
            if (!$request->hasFile('image')) {
                return redirect()->back()->with('error', 'Image is required')->withInput();
            }

            // Extract file dan upload
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = now()->format('YmdHis') . '.' . $extension;
            $image->storeAs('images/promotions', $imageName, 'public');
            $pathUrl = asset('storage/images/promotions');

            // Prepare data untuk schema (exclude file dari request)
            $requestData = $request->except('image');
            $requestData['image'] = $imageName; // Override dengan nama file (string)
            $requestData['path'] = $pathUrl;

            // Validasi dan hydrate schema
            $schema = new \App\Schemas\PromotionsSchema();
            $schema->hydrateSchemaBody($requestData);
            $schema->validate();
            $schema->hydrate();

            $this->promotionsRepository->createPromotion($schema);

            return redirect()->route('promotions.index')->with('success', 'Promotion created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, hapus file yang sudah di-upload
            if (isset($imageName) && Storage::disk('public')->exists('images/promotions/' . $imageName)) {
                Storage::disk('public')->delete('images/promotions/' . $imageName);
            }
            return redirect()->back()->withErrors($e->errors())->withInput($request->except('image'));
        } catch (\Throwable $th) {
            // Jika error lain, hapus file yang sudah di-upload
            if (isset($imageName) && Storage::disk('public')->exists('images/promotions/' . $imageName)) {
                Storage::disk('public')->delete('images/promotions/' . $imageName);
            }
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('image'))
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
            $promotion = $this->promotionsRepository->show($id);
            if (!$promotion || $promotion instanceof \Throwable) {
                return redirect()->back()->with('error', 'Promotion Not Found');
            }
            return view('backoffice.pages.promotions.edit', compact('promotion'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to retrieve promotion: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $promotion = $this->promotionsRepository->show($id);
            if (!$promotion || $promotion instanceof \Throwable) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'Promotion not found');
            }

            // Prepare data dasar (exclude file dari request)
            $data = [
                'id'      => $promotion->id,
                'name'    => $request->input('name', $promotion->name),
                'desc'    => $request->input('desc', $promotion->desc),
                'image'   => $promotion->image, // Default: gunakan image lama
                'code'    => $request->input('code', $promotion->code),
                'expired' => $request->input('expired', $promotion->expired),
            ];

            $newImageName = null;
            // Handle upload file jika ada
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $newImageName = now()->format('YmdHis') . '.' . $extension;
                $request->file('image')->storeAs('images/promotions', $newImageName, 'public');
                $data['image'] = $newImageName; // Override dengan nama file baru (string)
                $data['path'] = asset('storage/images/promotions');
            } else {
                $data['path'] = asset('storage/images/promotions');
            }

            // Validasi dan hydrate schema
            $schema = new \App\Schemas\PromotionsSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Jika validasi gagal, hapus file baru yang sudah di-upload
                if ($newImageName) {
                    $filePath = 'images/promotions/' . $newImageName;
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput($request->except('image'));
            }
            $schema->hydrate();

            // Setelah validasi sukses, baru hapus gambar lama kalau ada upload gambar baru
            if ($newImageName && $promotion->image) {
                $oldFilePath = 'images/promotions/' . $promotion->image;
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }

            $updateData = [
                'name'    => $schema->getName(),
                'desc'    => $schema->getDesc(),
                'image'   => $schema->getImage(),
                'code'    => $schema->getCode(),
                'expired' => $schema->getExpired(),
            ];

            $this->promotionsRepository->update($id, $updateData);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully');

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\DB::rollBack();
            // Hapus file baru jika ada error
            if (isset($newImageName) && Storage::disk('public')->exists('images/promotions/' . $newImageName)) {
                Storage::disk('public')->delete('images/promotions/' . $newImageName);
            }
            return redirect()->back()->with('error', 'Failed to update promotion: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $promotion = $this->promotionsRepository->show($id);
            if (!$promotion || $promotion instanceof \Throwable) {
                return redirect()->back()->with('error', 'Promotion not found');
            }

            // Hapus file gambar jika ada
            if (!empty($promotion->image)) {
                $filePath = 'images/promotions/' . $promotion->image;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $this->promotionsRepository->destroy($id);
            return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Failed to delete promotion: ' . $th->getMessage());
        }
    }
}
