<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Repositories\OrganizationRepository;
use App\Commons\Controller\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrganizationController extends BaseController
{
    public function __construct(
        private OrganizationRepository $organizationRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $organizations = $this->organizationRepository->paginate(request());
            return view('backoffice.pages.organization.index', compact('organizations'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to retrieve organizations.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('backoffice.pages.organization.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return redirect()->back()->with('error', 'Image is required')->withInput();
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $dateNow = now()->format('YmdHis');
            $fileName = $dateNow . '.' . $extension;

            $file->storeAs('images/organization', $fileName, 'public');

            $pathUrl = asset('storage/images/organization');

            $formattedData = array_merge($request->all(), [
                'image' => $fileName,
                'path' => $pathUrl
            ]);

            $schema = new \App\Schemas\OrganizationSchemas();
            $schema->hydrateSchemaBody($formattedData);
            $schema->validate();
            $schema->hydrate();

            $this->organizationRepository->createOrganization($schema);

            return redirect()->route('organizations.index')->with('success', 'Organization created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create organization: ' . $th->getMessage())->withInput();
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
            $organization = $this->organizationRepository->show($id);
            if (!$organization || $organization instanceof \Throwable) {
                return redirect()->back()->with('error', 'Organization Not Found');
            }
            return view('backoffice.pages.organization.edit', compact('organization'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to retrieve organization: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $organization = $this->organizationRepository->show($id);
            if (!$organization || $organization instanceof \Throwable) {
                return redirect()->back()->with('error', 'Organization not found');
            }

            $this->organizationRepository->update($id, $request);

            return redirect()->route('organizations.index')->with('success', 'Organization updated successfully');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput($request->except('image'));
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error($th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()
                ->back()
                ->withInput($request->except('image'))
                ->with('error', 'Terjadi kesalahan sistem');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $find_data = $this->organizationRepository->show($id);
            if (!$find_data || $find_data instanceof \Throwable) {
                return redirect()->back()->with('error', 'Organization not found');
            }
            $this->organizationRepository->destroy($id);
            return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete organization: ' . $th->getMessage());
        }
    }
}
