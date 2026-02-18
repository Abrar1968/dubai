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

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->mediaService->uploadImage(
                $request->file('featured_image'),
                'typing/services',
                1200,
                600
            );
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $this->mediaService->uploadImage(
                    $image,
                    'typing/services/gallery',
                    800,
                    600
                );
            }
            $data['gallery_images'] = $galleryPaths;
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

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($service->featured_image) {
                $this->mediaService->deleteImage($service->featured_image);
            }

            $data['featured_image'] = $this->mediaService->uploadImage(
                $request->file('featured_image'),
                'typing/services',
                1200,
                600
            );
        }

        // Handle gallery images
        $currentGallery = $service->gallery_images ?? [];

        // Remove selected images
        if ($request->has('remove_gallery')) {
            $toRemove = $request->input('remove_gallery');
            foreach ($toRemove as $index) {
                if (isset($currentGallery[$index])) {
                    $this->mediaService->deleteImage($currentGallery[$index]);
                    unset($currentGallery[$index]);
                }
            }
            $currentGallery = array_values($currentGallery); // Re-index array
        }

        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGallery[] = $this->mediaService->uploadImage(
                    $image,
                    'typing/services/gallery',
                    800,
                    600
                );
            }
        }

        $data['gallery_images'] = $currentGallery;

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
        // Delete featured image
        if ($service->featured_image) {
            $this->mediaService->deleteImage($service->featured_image);
        }

        // Delete gallery images
        if ($service->gallery_images) {
            foreach ($service->gallery_images as $image) {
                $this->mediaService->deleteImage($image);
            }
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
