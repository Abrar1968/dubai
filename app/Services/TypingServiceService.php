<?php

namespace App\Services;

use App\Models\TypingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TypingServiceService
{
    /**
     * Get all typing services ordered (excluding family visa).
     */
    public function list(): Collection
    {
        return TypingService::where('slug', '!=', 'family-visa-process')
            ->ordered()
            ->get();
    }

    /**
     * Get all active typing services ordered (excluding family visa for admin).
     */
    public function getActive(): Collection
    {
        return TypingService::where('slug', '!=', 'family-visa-process')
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get all active typing services ordered (INCLUDING family visa for public display).
     */
    public function getActiveWithFamilyVisa(): Collection
    {
        return TypingService::active()
            ->ordered()
            ->get();
    }

    /**
     * Get featured typing services (excluding family visa for admin).
     */
    public function getFeatured(int $limit = 4): Collection
    {
        return TypingService::where('slug', '!=', 'family-visa-process')
            ->active()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Get featured typing services (INCLUDING family visa for public display).
     */
    public function getFeaturedWithFamilyVisa(int $limit = 4): Collection
    {
        return TypingService::active()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Find a typing service by slug.
     */
    public function getBySlug(string $slug): TypingService
    {
        return TypingService::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a typing service by ID.
     */
    public function getById(int $id): TypingService
    {
        return TypingService::findOrFail($id);
    }

    /**
     * Create a new typing service.
     */
    public function create(array $data): TypingService
    {
        // Set default sort_order if not provided
        if (! isset($data['sort_order'])) {
            $data['sort_order'] = TypingService::max('sort_order') + 1;
        }

        return TypingService::create($data);
    }

    /**
     * Update a typing service.
     */
    public function update(TypingService $service, array $data): TypingService
    {
        $service->update($data);

        return $service->fresh();
    }

    /**
     * Delete a typing service.
     */
    public function delete(TypingService $service): bool
    {
        return $service->delete();
    }

    /**
     * Toggle the active status of a typing service.
     */
    public function toggleActive(TypingService $service): TypingService
    {
        $service->update(['is_active' => ! $service->is_active]);

        return $service->fresh();
    }

    /**
     * Toggle the featured status of a typing service.
     */
    public function toggleFeatured(TypingService $service): TypingService
    {
        $service->update(['is_featured' => ! $service->is_featured]);

        return $service->fresh();
    }

    /**
     * Reorder typing services.
     */
    public function reorder(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                TypingService::where('id', $id)->update(['sort_order' => $index]);
            }
        });
    }

    /**
     * Get statistics for typing services (excluding family visa).
     */
    public function getStats(): array
    {
        return [
            'total' => TypingService::where('slug', '!=', 'family-visa-process')->count(),
            'active' => TypingService::where('slug', '!=', 'family-visa-process')->active()->count(),
            'featured' => TypingService::where('slug', '!=', 'family-visa-process')->featured()->count(),
            'inactive' => TypingService::where('slug', '!=', 'family-visa-process')->inactive()->count(),
        ];
    }
}
