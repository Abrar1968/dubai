<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use App\Services\OfficeLocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeLocationController extends Controller
{
    public function __construct(
        protected OfficeLocationService $officeLocationService
    ) {}

    /**
     * Display a listing of office locations.
     */
    public function index(Request $request): View
    {
        $section = $request->get('section');
        $locations = $this->officeLocationService->list($section);

        return view('admin.pages.office-locations.index', [
            'locations' => $locations,
            'currentSection' => $section,
            'stats' => [
                'total' => $locations->count(),
                'active' => $locations->where('is_active', true)->count(),
                'global' => $locations->where('section', 'global')->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new office location.
     */
    public function create(): View
    {
        return view('admin.pages.office-locations.create');
    }

    /**
     * Store a newly created office location.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section' => ['required', 'string', 'in:global,hajj,tour,typing'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $this->officeLocationService->create($validated);

        return redirect()
            ->route('admin.office-locations.index')
            ->with('success', 'Office location created successfully.');
    }

    /**
     * Show the form for editing the specified office location.
     */
    public function edit(OfficeLocation $officeLocation): View
    {
        return view('admin.pages.office-locations.edit', [
            'location' => $officeLocation,
        ]);
    }

    /**
     * Update the specified office location.
     */
    public function update(Request $request, OfficeLocation $officeLocation): RedirectResponse
    {
        $validated = $request->validate([
            'section' => ['required', 'string', 'in:global,hajj,tour,typing'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $this->officeLocationService->update($officeLocation, $validated);

        return redirect()
            ->route('admin.office-locations.index')
            ->with('success', 'Office location updated successfully.');
    }

    /**
     * Remove the specified office location.
     */
    public function destroy(OfficeLocation $officeLocation): RedirectResponse
    {
        $this->officeLocationService->delete($officeLocation);

        return redirect()
            ->route('admin.office-locations.index')
            ->with('success', 'Office location deleted successfully.');
    }

    /**
     * Toggle office location active status.
     */
    public function toggleActive(OfficeLocation $officeLocation): RedirectResponse
    {
        $officeLocation->update(['is_active' => !$officeLocation->is_active]);

        $status = $officeLocation->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Office location {$status} successfully.");
    }

    /**
     * Reorder office locations.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:office_locations,id'],
        ]);

        $this->officeLocationService->reorder($request->input('order'));

        return back()->with('success', 'Office locations reordered successfully.');
    }
}
