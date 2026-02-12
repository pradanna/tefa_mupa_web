<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\Controller\BaseController;
use App\Repositories\HistoryRepository;

class HistoryController extends BaseController
{

    public function __construct(
        protected HistoryRepository $historyRepository
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $history = $this->historyRepository->findFirst();
            return view('backoffice.pages.history.index', compact('history'));
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th);
            return redirect()->back()->withErrors('error', 'Error' . $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // The create form is integrated into the index page.
        return redirect()->route('history.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if history already exists, since it's a singleton
        $existingHistory = $this->historyRepository->findFirst();
        if ($existingHistory) {
            return redirect()->route('history.index')->with('error', 'Data sejarah sudah ada. Silakan edit data yang ada.');
        }

        $imageName = null;
        try {
            // 1. Validate file presence
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Gambar wajib diunggah.')->withInput();
            }

            // 2. Process file upload
            $image = $request->file('file');
            $extension = $image->getClientOriginalExtension();
            $imageName = now()->format('YmdHis') . '.' . $extension;
            $destinationPath = public_path('images/history');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $pathUrl = asset('images/history');

            // 3. Prepare full payload for validation
            $payload = $request->all();
            $payload['image'] = $imageName;
            $payload['path'] = $pathUrl;

            // 4. Validate and hydrate schema
            $schema = new \App\Schemas\HistorySchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();
            $schema->hydrate();

            // 5. Save to database
            $this->historyRepository->createHistory($schema);

            return redirect()->route('history.index')->with('success', 'Data sejarah berhasil dibuat.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, delete the uploaded file
            if ($imageName && file_exists(public_path('images/history/' . $imageName))) {
                unlink(public_path('images/history/' . $imageName));
            }
            return redirect()->back()->withErrors($e->errors())->withInput($request->except('file'));
        } catch (\Throwable $th) {
            if ($imageName && file_exists(public_path('images/history/' . $imageName))) {
                unlink(public_path('images/history/' . $imageName));
            }
            \Illuminate\Support\Facades\Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->withInput($request->except('file'))->with('error', 'Terjadi kesalahan sistem saat membuat data sejarah.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $findContent = $this->historyRepository->show($id);
            if (!$findContent) {
                return redirect()->back()->with('error', 'History not found');
            }

            $payload = $request->all();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $name = now()->format('YmdHis');
                $filename = $name . '.' . $extension;

                $destinationPath = public_path('images/history');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $payload['image'] = $filename;
                $payload['path'] = asset('images/history');
            } else {
                $payload['image'] = $findContent->image;
                $payload['path'] = $findContent->path;
            }

            $payload['body'] = $request->input('body');
            $payload['title'] = $request->input('title');

            $schema = new \App\Schemas\HistorySchema();
            $schema->hydrateSchemaBody($payload);
            $schema->validate();
            $schema->hydrate();

            $this->historyRepository->updateData($id, $schema);

            return redirect()->route('history.index')->with('success', 'History updated successfully');
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
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
        //
    }
}
