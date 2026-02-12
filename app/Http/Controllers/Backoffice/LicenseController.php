<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\LicenseRepository;
use Illuminate\Support\Facades\Log;
use App\Schemas\LicenseSchema;

class LicenseController extends BaseController
{
    public function __construct(protected LicenseRepository $licenseRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $licenses = $this->licenseRepository->findAll(request());

            // Pastikan $licenses adalah instance dari LengthAwarePaginator
            if (!$licenses instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                throw new \Exception('Invalid pagination result');
            }

            return view('backoffice.pages.license.index', compact('licenses'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data lisensi: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('backoffice.pages.license.create');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal memuat form tambah lisensi.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $fileName = null;
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Upload File ke public/images/licenses
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = now()->format('YmdHis') . '.' . $extension;
            $destinationPath = public_path('images/licenses');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);

            // Prepare data
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'file' => $fileName,
            ];

            // Validasi dan hydrate schema
            $schema = new LicenseSchema();
            $schema->hydrateSchemaBody($data);
            $schema->validate();
            $schema->hydrate();

            $this->licenseRepository->createLicense($schema);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Hapus file jika validasi gagal
            if ($fileName && file_exists(public_path('images/licenses/' . $fileName))) {
                unlink(public_path('images/licenses/' . $fileName));
            }
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            // Hapus file jika terjadi error sistem
            if ($fileName && file_exists(public_path('images/licenses/' . $fileName))) {
                unlink(public_path('images/licenses/' . $fileName));
            }
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput()
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
            $license = $this->licenseRepository->show($id);
            if (!$license || $license instanceof \Throwable) {
                return redirect()->back()->with('error', 'Lisensi tidak ditemukan');
            }
            return view('backoffice.pages.license.edit', compact('license'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data lisensi: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        $newFileName = null;
        try {
            $license = $this->licenseRepository->show($id);
            if (!$license || $license instanceof \Throwable) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->with('error', 'Lisensi tidak ditemukan');
            }

            // Prepare data untuk schema
            $data = [
                'id' => $license->id,
                'name' => $request->input('name', $license->name),
                'code' => $request->input('code', $license->code),
                'type' => $request->input('type', $license->type),
                'file' => $license->file, // Default file lama
            ];

            // Handle Upload File Baru
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $newFileName = now()->format('YmdHis') . '.' . $extension;
                $destinationPath = public_path('images/licenses');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $newFileName);
                $data['file'] = $newFileName;
            }

            // Validasi dan hydrate schema
            $schema = new LicenseSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Hapus file baru jika validasi gagal
                if ($newFileName && file_exists(public_path('images/licenses/' . $newFileName))) {
                    unlink(public_path('images/licenses/' . $newFileName));
                }
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            $schema->hydrate();

            $this->licenseRepository->updateLicense($id, $schema);

            // Hapus file lama jika ada file baru yang berhasil diupload
            if ($newFileName && $license->file) {
                $oldFilePath = public_path('images/licenses/' . $license->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil diperbarui');
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\DB::rollBack();
            // Hapus file baru jika error
            if ($newFileName && file_exists(public_path('images/licenses/' . $newFileName))) {
                unlink(public_path('images/licenses/' . $newFileName));
            }
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal memperbarui lisensi: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $license = $this->licenseRepository->show($id);
            if (!$license || $license instanceof \Throwable) {
                return redirect()->back()->with('error', 'Lisensi tidak ditemukan');
            }

            // Hapus file fisik
            if ($license->file) {
                $filePath = public_path('images/licenses/' . $license->file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->licenseRepository->delete($id);
            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal menghapus lisensi: ' . $th->getMessage());
        }
    }
}
