<?php

namespace App\Services;

use App\Models\OfficeLocation;
use Illuminate\Support\Collection;

class OfficeLocationService
{
    /**
     * Get all office locations.
     */
    public function list(?string $section = null, bool $activeOnly = false): Collection
    {
        $query = OfficeLocation::ordered();

        if ($section) {
            $query->section($section);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get an office location by ID.
     */
    public function getById(int $id): OfficeLocation
    {
        return OfficeLocation::findOrFail($id);
    }

    /**
     * Create a new office location.
     */
    public function create(array $data): OfficeLocation
    {
        // Set sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = OfficeLocation::max('sort_order') + 1;
        }

        return OfficeLocation::create($data);
    }

    /**
     * Update an office location.
     */
    public function update(OfficeLocation $location, array $data): OfficeLocation
    {
        $location->update($data);

        return $location->fresh();
    }

    /**
     * Delete an office location.
     */
    public function delete(OfficeLocation $location): bool
    {
        return $location->delete();
    }

    /**
     * Reorder office locations.
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            OfficeLocation::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    /**
     * Get active locations for a section (public display).
     */
    public function getForSection(string $section): Collection
    {
        return OfficeLocation::active()->section($section)->ordered()->get();
    }

    /**
     * Get global office locations (shown across all sections).
     */
    public function getGlobal(): Collection
    {
        return OfficeLocation::active()->section('global')->ordered()->get();
    }

    /**
     * Get offices for display on a section's home page.
     * Returns both section-specific and global offices.
     */
    public function getForHomePage(string $section): Collection
    {
        return OfficeLocation::active()
            ->where(function ($query) use ($section) {
                $query->where('section', $section)
                    ->orWhere('section', 'global');
            })
            ->ordered()
            ->get();
    }

    /**
     * Get all active locations (public display).
     */
    public function getActive(): Collection
    {
        return OfficeLocation::active()->ordered()->get();
    }

    /**
     * Get location count.
     */
    public function getCount(): int
    {
        return OfficeLocation::count();
    }

    /**
     * Get statistics for office locations.
     */
    public function getStats(): array
    {
        return [
            'total' => OfficeLocation::count(),
            'global' => OfficeLocation::where('section', 'global')->count(),
            'hajj' => OfficeLocation::where('section', 'hajj')->count(),
            'typing' => OfficeLocation::where('section', 'typing')->count(),
            'active' => OfficeLocation::active()->count(),
        ];
    }
}
