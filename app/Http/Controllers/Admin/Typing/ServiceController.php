<?php

namespace App\Http\Controllers\Admin\Typing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TypingServiceRequest;
use App\Models\TypingService;
use App\Services\MediaService;
use App\Services\TypingServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(
        protected TypingServiceService $typingServiceService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display a listing of typing services.
     */
    public function index(Request $request): View
    {
        $services = $this->typingServiceService->list();

        return view('admin.pages.typing.services.index', [
            'services' => $services,
            'stats' => [
                'total' => $services->count(),
                'active' => $services->where('is_active', true)->count(),
                'featured' => $services->where('is_featured', true)->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new typing service.
     */
    public function create(): View
    {
        return view('admin.pages.typing.services.create');
    }

    /**
     * Store a newly created typing service.
     */
    public function store(TypingServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'typing/services',
                800,
                600
            );
        }

        $this->typingServiceService->create($data);

        return redirect()
            ->route('admin.typing.services.index')
            ->with('success', 'Typing service created successfully.');
    }

    /**
     * Display the specified typing service.
     */
    public function show(TypingService $service): View
    {
        return view('admin.pages.typing.services.show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified typing service.
     */
    public function edit(TypingService $service): View
    {
        return view('admin.pages.typing.services.edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update the specified typing service.
     */
    public function update(TypingServiceRequest $request, TypingService $service): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                $this->mediaService->deleteImage($service->image);
            }

            $data['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'typing/services',
                800,
                600
            );
        }

        $this->typingServiceService->update($service, $data);

        return redirect()
            ->route('admin.typing.services.index')
            ->with('success', 'Typing service updated successfully.');
    }

    /**
     * Remove the specified typing service.
     */
    public function destroy(TypingService $service): RedirectResponse
    {
        // Delete image
        if ($service->image) {
            $this->mediaService->deleteImage($service->image);
        }

        $this->typingServiceService->delete($service);

        return redirect()
            ->route('admin.typing.services.index')
            ->with('success', 'Typing service deleted successfully.');
    }

    /**
     * Toggle typing service active status.
     */
    public function toggleStatus(TypingService $service): RedirectResponse
    {
        $this->typingServiceService->toggleActive($service);

        $status = $service->fresh()->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Typing service {$status} successfully.");
    }

    /**
     * Toggle typing service featured status.
     */
    public function toggleFeatured(TypingService $service): RedirectResponse
    {
        $this->typingServiceService->toggleFeatured($service);

        $status = $service->fresh()->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', "Typing service marked as {$status}.");
    }

    /**
     * Reorder typing services.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:typing_services,id'],
        ]);

        $this->typingServiceService->reorder($request->input('order'));

        return back()->with('success', 'Services reordered successfully.');
    }
}
