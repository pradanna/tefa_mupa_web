<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\PatnersRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PatnerController extends BaseController
{
    public function __construct(protected PatnersRepository $patnersRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $partners = $this->patnersRepository->findAll(request());
            return view('backoffice.pages.partner.index', compact('partners'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data partner: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('backoffice.pages.partner.create');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal memuat form tambah partner.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi file terlebih dahulu
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Gambar wajib diisi')->withInput();
            }

            // Extract file dan upload
            $image = $request->file('file');
            $extension = $image->getClientOriginalExtension();
            $imageName = now()->format('YmdHis') . '.' . $extension;
            $image->storeAs('images/partners', $imageName, 'public');

            // Prepare data untuk schema (exclude file dari request)
            $requestData = $request->except('file');
            $requestData['image'] = $imageName; // Override dengan nama file (string)

            // Validasi dan hydrate schema
            $schema = new \App\Schemas\PatnersSchema();
            $schema->hydrateSchemaBody($requestData);
            $schema->validate();
            $schema->hydrate();

            $this->patnersRepository->createPartner($schema);

            return redirect()->route('partners.index')->with('success', 'Partner berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, hapus file yang sudah di-upload
            if (isset($imageName) && Storage::disk('public')->exists('images/partners/' . $imageName)) {
                Storage::disk('public')->delete('images/partners/' . $imageName);
            }
            return redirect()->back()->withErrors($e->errors())->withInput($request->except('file'));
        } catch (\Throwable $th) {
            // Jika error lain, hapus file yang sudah di-upload
            if (isset($imageName) && Storage::disk('public')->exists('images/partners/' . $imageName)) {
                Storage::disk('public')->delete('images/partners/' . $imageName);
            }
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('file'))
                ->with('error', 'Terjadi kesalahan sistem: ' . $th->getMessage());
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
            $partner = $this->patnersRepository->show($id);
            if (!$partner || $partner instanceof \Throwable) {
                return redirect()->back()->with('error', 'Partner tidak ditemukan');
            }
            return view('backoffice.pages.partner.edit', compact('partner'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data partner: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $partner = $this->patnersRepository->show($id);
            if (!$partner || $partner instanceof \Throwable) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'Partner tidak ditemukan');
            }

            // Prepare data untuk schema
            $data = [
                'name' => $request->input('name', $partner->name),
                'image' => $partner->image,
            ];

            $newImageName = null;
            $oldFilePath = null;
            if ($request->hasFile('file')) {
                // Simpan info gambar lama untuk dihapus setelah validasi sukses
                $oldFilePath = $partner->image ? 'images/partners/' . $partner->image : null;
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $newImageName = now()->format('YmdHis') . '.' . $extension;
                $file->storeAs('images/partners', $newImageName, 'public');
                $data['image'] = $newImageName;
            }

            // Validasi dan hydrate schema
            $schema = new \App\Schemas\PatnersSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Jika validasi gagal, hapus file baru yang sudah di-upload
                if ($newImageName && Storage::disk('public')->exists('images/partners/' . $newImageName)) {
                    Storage::disk('public')->delete('images/partners/' . $newImageName);
                }
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            $schema->hydrate();

            $this->patnersRepository->updatePartner($id, $schema);

            // Setelah update sukses, hapus gambar lama jika ada gambar baru
            if ($newImageName && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('partners.index')->with('success', 'Partner berhasil diperbarui');

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\DB::rollBack();
            // Jika error, hapus file baru yang sudah di-upload
            if (isset($newImageName) && Storage::disk('public')->exists('images/partners/' . $newImageName)) {
                Storage::disk('public')->delete('images/partners/' . $newImageName);
            }
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal memperbarui partner: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $partner = $this->patnersRepository->show($id);
            if (!$partner || $partner instanceof \Throwable) {
                return redirect()->back()->with('error', 'Partner tidak ditemukan');
            }

            // Hapus gambar dari storage
            if ($partner->image) {
                $filePath = 'images/partners/' . $partner->image;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $this->patnersRepository->delete($id);
            return redirect()->route('partners.index')->with('success', 'Partner berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal menghapus partner: ' . $th->getMessage());
        }
    }
}
