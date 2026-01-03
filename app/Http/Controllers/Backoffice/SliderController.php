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
    public function create()
    {
        return view('backoffice.pages.slider.created');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $slider = $this->sliderRepository->create($request);
            return redirect()->route('sliders.index')->with('success', 'Slider created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Slider created failed: ' . $th->getMessage());
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
