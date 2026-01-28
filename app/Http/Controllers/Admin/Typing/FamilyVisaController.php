<?php

namespace App\Http\Controllers\Admin\Typing;

use App\Http\Controllers\Controller;
use App\Models\FamilyVisaEmirate;
use App\Models\FamilyVisaType;
use App\Services\FamilyVisaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FamilyVisaController extends Controller
{
    public function __construct(
        protected FamilyVisaService $familyVisaService
    ) {}

    // ========== EMIRATES ==========

    /**
     * Display a listing of emirates.
     */
    public function index(): View
    {
        $emirates = $this->familyVisaService->listEmirates();

        return view('admin.pages.typing.family-visa.index', [
            'emirates' => $emirates,
            'stats' => [
                'total' => $emirates->count(),
                'active' => $emirates->where('is_active', true)->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new emirate.
     */
    public function create(): View
    {
        return view('admin.pages.typing.family-visa.create');
    }

    /**
     * Store a newly created emirate.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:family_visa_emirates,slug'],
            'description' => ['nullable', 'string', 'max:2000'],
            'intro_text' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $this->familyVisaService->createEmirate($validated);

        return redirect()
            ->route('admin.typing.family-visa.index')
            ->with('success', 'Emirate created successfully.');
    }

    /**
     * Show emirate with its visa types.
     */
    public function show(FamilyVisaEmirate $family_visa): View
    {
        $emirate = $family_visa;
        $visaTypes = $this->familyVisaService->listVisaTypes($emirate->id);

        return view('admin.pages.typing.family-visa.show', [
            'emirate' => $emirate,
            'visaTypes' => $visaTypes,
        ]);
    }

    /**
     * Show the form for editing an emirate.
     */
    public function edit(FamilyVisaEmirate $family_visa): View
    {
        return view('admin.pages.typing.family-visa.edit', [
            'emirate' => $family_visa,
        ]);
    }

    /**
     * Update the specified emirate.
     */
    public function update(Request $request, FamilyVisaEmirate $family_visa): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:family_visa_emirates,slug,' . $family_visa->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'intro_text' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $this->familyVisaService->updateEmirate($family_visa, $validated);

        return redirect()
            ->route('admin.typing.family-visa.index')
            ->with('success', 'Emirate updated successfully.');
    }

    /**
     * Remove the specified emirate.
     */
    public function destroy(FamilyVisaEmirate $family_visa): RedirectResponse
    {
        $this->familyVisaService->deleteEmirate($family_visa);

        return redirect()
            ->route('admin.typing.family-visa.index')
            ->with('success', 'Emirate deleted successfully.');
    }

    /**
     * Toggle emirate active status.
     */
    public function toggleActive(FamilyVisaEmirate $family_visa): RedirectResponse
    {
        $this->familyVisaService->toggleEmirateActive($family_visa);

        $status = $family_visa->fresh()->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Emirate {$status} successfully.");
    }

    // ========== VISA TYPES ==========

    /**
     * Show form to create visa type for an emirate.
     */
    public function createType(FamilyVisaEmirate $family_visa): View
    {
        return view('admin.pages.typing.family-visa.types.create', [
            'emirate' => $family_visa,
        ]);
    }

    /**
     * Store a new visa type.
     */
    public function storeType(Request $request, FamilyVisaEmirate $family_visa): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'long_description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['string'],
            'process_steps' => ['nullable', 'array'],
            'process_steps.*.title' => ['string'],
            'process_steps.*.description' => ['nullable', 'string'],
            'processing_time' => ['nullable', 'string', 'max:255'],
            'price_range' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['emirate_id'] = $family_visa->id;
        $validated['is_active'] = $request->boolean('is_active');

        // Filter out empty requirements/documents
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter($validated['requirements']));
        }
        if (isset($validated['documents'])) {
            $validated['documents'] = array_values(array_filter($validated['documents']));
        }
        if (isset($validated['process_steps'])) {
            $validated['process_steps'] = array_values(array_filter($validated['process_steps'], fn($s) => !empty($s['title'])));
        }

        $this->familyVisaService->createVisaType($validated);

        return redirect()
            ->route('admin.typing.family-visa.show', $family_visa)
            ->with('success', 'Visa type created successfully.');
    }

    /**
     * Show form to edit visa type.
     */
    public function editType(FamilyVisaEmirate $family_visa, FamilyVisaType $visaType): View
    {
        $visaType->load('emirate');
        $emirates = $this->familyVisaService->listEmirates();

        return view('admin.pages.typing.family-visa.types.edit', [
            'visaType' => $visaType,
            'emirate' => $family_visa,
            'emirates' => $emirates,
        ]);
    }

    /**
     * Update a visa type.
     */
    public function updateType(Request $request, FamilyVisaEmirate $family_visa, FamilyVisaType $visaType): RedirectResponse
    {
        $validated = $request->validate([
            'emirate_id' => ['required', 'exists:family_visa_emirates,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'long_description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['string'],
            'process_steps' => ['nullable', 'array'],
            'process_steps.*.title' => ['string'],
            'process_steps.*.description' => ['nullable', 'string'],
            'processing_time' => ['nullable', 'string', 'max:255'],
            'price_range' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Filter out empty requirements/documents
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter($validated['requirements']));
        }
        if (isset($validated['documents'])) {
            $validated['documents'] = array_values(array_filter($validated['documents']));
        }
        if (isset($validated['process_steps'])) {
            $validated['process_steps'] = array_values(array_filter($validated['process_steps'], fn($s) => !empty($s['title'])));
        }

        $this->familyVisaService->updateVisaType($visaType, $validated);

        return redirect()
            ->route('admin.typing.family-visa.show', $visaType->emirate_id)
            ->with('success', 'Visa type updated successfully.');
    }

    /**
     * Delete a visa type.
     */
    public function destroyType(FamilyVisaEmirate $family_visa, FamilyVisaType $visaType): RedirectResponse
    {
        $emirateId = $visaType->emirate_id;
        $this->familyVisaService->deleteVisaType($visaType);

        return redirect()
            ->route('admin.typing.family-visa.show', $emirateId)
            ->with('success', 'Visa type deleted successfully.');
    }

    /**
     * Toggle visa type active status.
     */
    public function toggleTypeActive(FamilyVisaEmirate $family_visa, FamilyVisaType $visaType): RedirectResponse
    {
        $this->familyVisaService->toggleVisaTypeActive($visaType);

        $status = $visaType->fresh()->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Visa type {$status} successfully.");
    }
}
