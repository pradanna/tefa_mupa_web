<?php

namespace App\Http\Controllers\Backoffice;

use App\Commons\Controller\BaseController;
use App\Providers\AppServiceProvider;
use App\Repositories\ContactRepository;
use App\Schemas\ContactSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ContactController extends BaseController
{
    public function __construct(
        protected ContactRepository $contactRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $contacts = $this->contactRepository->getContact();
            return $this->makeView('backoffice.pages.contacts.index', compact('contacts'));
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->makeView('backoffice.pages.contacts.index', ['contacts' => []]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->makeView('backoffice.pages.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $payload = $request->all();
            $payload['id_user'] = Auth::id();

            if (empty($payload['status'])) {
                $payload['status'] = 'publis';
            }

            $schema = new ContactSchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();
            $schema->hydrate();

            $this->contactRepository->createOrUpdateFromSchema($schema);
            Cache::forget(AppServiceProvider::FOOTER_CONTACT_CACHE_KEY);

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Informasi kontak berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan informasi kontak');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $contact = $this->contactRepository->show($id);
            if (!$contact || $contact instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data kontak tidak ditemukan');
            }

            return $this->makeView('backoffice.pages.contacts.edit', compact('contact'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data kontak');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $exists = $this->contactRepository->show($id);
            if (!$exists || $exists instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data kontak tidak ditemukan');
            }

            $payload = $request->all();
            $payload['id_user'] = Auth::id();

            if (empty($payload['status'])) {
                $payload['status'] = $exists->status ?? 'publis';
            }

            $schema = new ContactSchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();
            $schema->hydrate();

            $this->contactRepository->createOrUpdateFromSchema($schema);
            Cache::forget(AppServiceProvider::FOOTER_CONTACT_CACHE_KEY);

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Informasi kontak berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengubah informasi kontak');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->contactRepository->show($id);
            if (!$data || $data instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data kontak tidak ditemukan');
            }

            $this->contactRepository->delete($id);
            Cache::forget(AppServiceProvider::FOOTER_CONTACT_CACHE_KEY);

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Informasi kontak berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus informasi kontak');
        }
    }
}


