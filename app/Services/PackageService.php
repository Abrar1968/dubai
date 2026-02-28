<?php

namespace App\Services;

use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PackageService
{
    /**
     * Get all packages.
     */
    public function list(?PackageType $type = null, bool $activeOnly = false): Collection
    {
        $query = Package::with('gallery')->orderBy('created_at', 'desc');

        if ($type) {
            $query->ofType($type);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get paginated packages.
     */
    public function paginate(int $perPage = 15, ?PackageType $type = null, bool $activeOnly = false): LengthAwarePaginator
    {
        $query = Package::with('gallery')->orderBy('created_at', 'desc');

        if ($type) {
            $query->ofType($type);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a package by ID.
     */
    public function getById(int $id): Package
    {
        return Package::with('gallery')->findOrFail($id);
    }

    /**
     * Get a package by slug.
     */
    public function getBySlug(string $slug): Package
    {
        return Package::with('gallery')->where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new package.
     */
    public function create(array $data): Package
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        return Package::create($data);
    }

    /**
     * Update a package.
     */
    public function update(Package $package, array $data): Package
    {
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $package->update($data);

        return $package->fresh();
    }

    /**
     * Delete a package.
     */
    public function delete(Package $package): bool
    {
        return $package->delete();
    }

    /**
     * Get featured packages.
     */
    public function getFeatured(int $limit = 6): Collection
    {
        return Package::active()->featured()->limit($limit)->get();
    }

    /**
     * Update gallery images.
     */
    public function updateGallery(Package $package, array $images): void
    {
        // Delete existing gallery
        $package->gallery()->delete();

        // Add new images
        foreach ($images as $index => $image) {
            $package->gallery()->create([
                'image_path' => $image['path'],
                'alt_text' => $image['alt'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Package $package): Package
    {
        $package->is_active = !$package->is_active;
        $package->save();

        return $package->fresh();
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Package $package): Package
    {
        // If trying to feature a package, check the limit
        if (!$package->is_featured) {
            $featuredCount = Package::where('is_featured', true)->count();

            if ($featuredCount >= 4) {
                throw new \Exception('Maximum 4 packages can be featured at a time across all types (Hajj, Umrah, and Tour combined).');
            }
        }

        $package->is_featured = !$package->is_featured;
        $package->save();

        return $package->fresh();
    }

    /**
     * Get package statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Package::count(),
            'active' => Package::active()->count(),
            'featured' => Package::featured()->count(),
            'hajj' => Package::ofType(PackageType::HAJJ)->count(),
            'umrah' => Package::ofType(PackageType::UMRAH)->count(),
            'tour' => Package::ofType(PackageType::TOUR)->count(),
        ];
    }
}
