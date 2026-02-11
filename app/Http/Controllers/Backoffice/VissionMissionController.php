<?php

namespace App\Http\Controllers\Backoffice;

use App\Commons\Controller\BaseController;
use App\Repositories\VisionMissionRepository;
use App\Schemas\VisionMissionSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VissionMissionController extends BaseController
{
    public function __construct(
        protected VisionMissionRepository $visionMissionRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $visionMissions = $this->visionMissionRepository->paginate(request());
            return $this->makeView('backoffice.pages.vision-mission.index', compact('visionMissions'));
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->makeView('backoffice.pages.vision-mission.index', ['visionMissions' => []]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->makeView('backoffice.pages.vision-mission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $schema = new VisionMissionSchema();
            $schema->hydrateSchemaBody($request->all());
            $schema->validate();
            $schema->hydrate();

            $this->visionMissionRepository->createFromSchema($schema);

            return redirect()
                ->route('vision-missions.index')
                ->with('success', 'Data visi/misi berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $th) {
            dd($th)
;            Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan visi/misi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visionMission = $this->visionMissionRepository->show($id);
        return $this->makeView('backoffice.pages.vision-mission.show', compact('visionMission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $visionMission = $this->visionMissionRepository->show($id);
            if (!$visionMission || $visionMission instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data visi/misi tidak ditemukan');
            }

            return $this->makeView('backoffice.pages.vision-mission.edit', compact('visionMission'));
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Gagal mengambil data visi/misi');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $exists = $this->visionMissionRepository->show($id);
            if (!$exists || $exists instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data visi/misi tidak ditemukan');
            }

            $schema = new VisionMissionSchema();
            $schema->hydrateSchemaBody($request->all());
            $schema->validate();
            $schema->hydrate();

            $this->visionMissionRepository->updateFromSchema((int) $id, $schema);

            return redirect()
                ->route('vision-missions.index')
                ->with('success', 'Data visi/misi berhasil diperbarui');
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
                ->with('error', 'Terjadi kesalahan saat mengubah visi/misi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->visionMissionRepository->show($id);
            if (!$data || $data instanceof \Throwable) {
                return redirect()->back()->with('error', 'Data visi/misi tidak ditemukan');
            }

            $this->visionMissionRepository->delete($id);

            return redirect()
                ->route('vision-missions.index')
                ->with('success', 'Data visi/misi berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data visi/misi');
        }
    }
}
