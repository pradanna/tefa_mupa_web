<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\LicenseRepository;
use Illuminate\Support\Facades\Log;

class LicenseController extends BaseController
{
    public function __construct(protected LicenseRepository $licenseRepository)
    {
    }

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
        try {
            // Validasi dan hydrate schema
            $schema = new \App\Schemas\LicenseSchema();
            $schema->hydrateSchemaBody($request->all());
            $schema->validate();
            $schema->hydrate();

            $this->licenseRepository->createLicense($schema);

            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
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
        \Illuminate\Support\Facades\DB::beginTransaction();
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
            ];

            // Validasi dan hydrate schema
            $schema = new \App\Schemas\LicenseSchema();
            $schema->hydrateSchemaBody($data);

            try {
                $schema->validate();
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            $schema->hydrate();

            $this->licenseRepository->updateLicense($id, $schema);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil diperbarui');

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\DB::rollBack();
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

            $this->licenseRepository->delete($id);
            return redirect()->route('licenses.index')->with('success', 'Lisensi berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal menghapus lisensi: ' . $th->getMessage());
        }
    }
}
