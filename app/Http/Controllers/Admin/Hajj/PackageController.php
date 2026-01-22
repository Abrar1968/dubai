<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Enums\PackageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\Package;
use App\Services\MediaService;
use App\Services\PackageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function __construct(
        protected PackageService $packageService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display a listing of packages.
     */
    public function index(Request $request): View
    {
        $type = $request->get('type') ? PackageType::tryFrom($request->get('type')) : null;
        $packages = $this->packageService->paginate(
            perPage: $request->get('per_page', 15),
            type: $type
        );

        return view('admin.pages.packages.index', [
            'packages' => $packages,
            'types' => PackageType::cases(),
            'currentType' => $type,
            'stats' => $this->packageService->getStats(),
        ]);
    }

    /**
     * Show the form for creating a new package.
     */
    public function create(): View
    {
        return view('admin.pages.packages.create', [
            'types' => PackageType::cases(),
        ]);
    }

    /**
     * Store a newly created package.
     */
    public function store(PackageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->mediaService->uploadImage(
                $request->file('thumbnail'),
                'packages/thumbnails',
                800,
                600
            );
        }

        $package = $this->packageService->create($data);

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $image) {
                $path = $this->mediaService->uploadImage($image, 'packages/gallery');
                $galleryImages[] = ['path' => $path, 'alt' => $package->title];
            }
            $this->packageService->updateGallery($package, $galleryImages);
        }

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package): View
    {
        $package->load('gallery', 'bookings');

        return view('admin.pages.packages.show', [
            'package' => $package,
        ]);
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package): View
    {
        $package->load('gallery');

        return view('admin.pages.packages.edit', [
            'package' => $package,
            'types' => PackageType::cases(),
        ]);
    }

    /**
     * Update the specified package.
     */
    public function update(PackageRequest $request, Package $package): RedirectResponse
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($package->thumbnail) {
                $this->mediaService->deleteImage($package->thumbnail);
            }

            $data['thumbnail'] = $this->mediaService->uploadImage(
                $request->file('thumbnail'),
                'packages/thumbnails',
                800,
                600
            );
        }

        $this->packageService->update($package, $data);

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $galleryImages = [];

            // Keep existing images
            $existingImages = $request->input('existing_gallery', []);
            foreach ($existingImages as $index => $imagePath) {
                $galleryImages[] = ['path' => $imagePath, 'alt' => $package->title];
            }

            // Add new images
            foreach ($request->file('gallery') as $image) {
                $path = $this->mediaService->uploadImage($image, 'packages/gallery');
                $galleryImages[] = ['path' => $path, 'alt' => $package->title];
            }

            $this->packageService->updateGallery($package, $galleryImages);
        }

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package): RedirectResponse
    {
        // Delete thumbnail
        if ($package->thumbnail) {
            $this->mediaService->deleteImage($package->thumbnail);
        }

        // Delete gallery images
        foreach ($package->gallery as $image) {
            $this->mediaService->deleteImage($image->image_path);
        }

        $this->packageService->delete($package);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    /**
     * Toggle package active status.
     */
    public function toggleStatus(Package $package): RedirectResponse
    {
        $this->packageService->update($package, [
            'is_active' => !$package->is_active,
        ]);

        $status = $package->fresh()->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Package {$status} successfully.");
    }

    /**
     * Toggle package featured status.
     */
    public function toggleFeatured(Package $package): RedirectResponse
    {
        $this->packageService->update($package, [
            'is_featured' => !$package->is_featured,
        ]);

        $status = $package->fresh()->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', "Package {$status} successfully.");
    }
}
