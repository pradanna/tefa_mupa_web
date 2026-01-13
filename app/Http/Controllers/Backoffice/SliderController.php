<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Repositories\SliderRepository;
use App\Commons\Controller\BaseController;
use Illuminate\Support\Facades\Storage;


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
        return  view('backoffice.pages.slider.created');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
            $schema->hydrate();

            $this->sliderRepository->createNews($schema);

            return redirect()->route('sliders.index')->with('success', 'Slider created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create slider: ' . $th->getMessage());
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

            $data = [
                'title' => $request->input('title', $slider->title),
                'file'  => $slider->file,
                'path'  => $slider->path,
            ];

            if ($request->hasFile('file')) {
                // Delete old file if exists
                $filePath = 'images/slider/' . $slider->file;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
                }
                // Store new file
                $extension = $request->file('file')->getClientOriginalExtension();
                $name = now()->format('YmdHis') . '.' . $extension;
                $request->file('file')->storeAs('images/slider', $name, 'public');
                $data['file'] = $name;
                $data['path'] = asset('storage/images/slider');
            }

            $schema = new \App\Schemas\SliderSchema();
            $schema->hydrateSchemaBody($data);
            $validator = $schema->validate();
            $schema->hydrate();

            $updateData = [
                'title' => $schema->getTitle(),
                'file'  => $schema->getFile(),
                'path'  => $schema->getPath()
            ];
            $this->sliderRepository->update($id, $updateData);
            return redirect()->route('sliders.index')->with('success', 'Slider updated successfully');
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
