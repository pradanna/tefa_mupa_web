<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Repositories\SliderRepository;
use App\Commons\Controller\BaseController;


class SliderController extends BaseController
{
    public function __construct(
        private SliderRepository $sliderRepository
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sliders = $this->sliderRepository->paginate(request());
            return view('backoffice.pages.slider.index', compact('sliders'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to retrieve sliders.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'File is required')->withInput();
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $dateNow = now()->format('YmdHis');
            $fileName = $dateNow . '.' . $extension;

            $file->storeAs('images/slider', $fileName, 'public');

            $pathUrl = asset('storage/images/slider');

            $formattedData = array_merge($request->all(), [
                'file' => $fileName,
                'path' => $pathUrl
            ]);

            $schema = new \App\Schemas\SliderSchema();
            $schema->hydrateSchemaBody($formattedData);

            $validator = $schema->validate();

            if ($validator->fails()) {
                // Jika validasi gagal, hapus file yang sudah diupload
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists('images/slider/' . $fileName)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete('images/slider/' . $fileName);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $schema->hydrateBody();

            $this->sliderRepository->createNews($schema);

            return redirect()->route('sliders.index')->with('success', 'Slider created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create slider: ' . $th->getMessage());
        }
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
        try {
            $slider = $this->sliderRepository->show($id);
            if (!$slider || $slider instanceof \Throwable) {
                return redirect()->back()->with('error', 'Slider Not Found');
            }
            return view('backoffice.pages.slider.edit', compact('slider'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to retrieve slider: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $slider = $this->sliderRepository->show($id);
            if(!$slider || $slider instanceof \Throwable){
                return redirect()->back()->with('error','Slider not found');
            }
            $this->sliderRepository->update($id, $request);
            return redirect()->route('sliders.index')->with('success', 'Slider updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update slider: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $find_data = $this->sliderRepository->show($id);
            if(!$find_data || $find_data instanceof \Throwable){
                return redirect()->back()->with('error', 'Slider not found');
            }
            $this->sliderRepository->delete($id);
            return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete slider: ' . $th->getMessage());
        }
    }
}
