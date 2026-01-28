<?php

namespace App\Services;

use App\Models\FamilyVisaEmirate;
use App\Models\FamilyVisaType;
use Illuminate\Support\Collection;

class FamilyVisaService
{
    /**
     * Get all emirates.
     */
    public function listEmirates(bool $activeOnly = false): Collection
    {
        $query = FamilyVisaEmirate::ordered();

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get active emirates with their active visa types.
     */
    public function getEmiratesWithTypes(): Collection
    {
        return FamilyVisaEmirate::active()
            ->ordered()
            ->with(['activeVisaTypes'])
            ->get();
    }

    /**
     * Get emirate by ID.
     */
    public function getEmirateById(int $id): FamilyVisaEmirate
    {
        return FamilyVisaEmirate::findOrFail($id);
    }

    /**
     * Get emirate by slug.
     */
    public function getEmirateBySlug(string $slug): FamilyVisaEmirate
    {
        return FamilyVisaEmirate::where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new emirate.
     */
    public function createEmirate(array $data): FamilyVisaEmirate
    {
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = FamilyVisaEmirate::max('sort_order') + 1;
        }

        return FamilyVisaEmirate::create($data);
    }

    /**
     * Update an emirate.
     */
    public function updateEmirate(FamilyVisaEmirate $emirate, array $data): FamilyVisaEmirate
    {
        $emirate->update($data);
        return $emirate->fresh();
    }

    /**
     * Delete an emirate.
     */
    public function deleteEmirate(FamilyVisaEmirate $emirate): bool
    {
        return $emirate->delete();
    }

    /**
     * Reorder emirates.
     */
    public function reorderEmirates(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            FamilyVisaEmirate::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    // ========== Visa Types ==========

    /**
     * Get all visa types for an emirate.
     */
    public function listVisaTypes(?int $emirateId = null, bool $activeOnly = false): Collection
    {
        $query = FamilyVisaType::with('emirate')->ordered();

        if ($emirateId) {
            $query->where('emirate_id', $emirateId);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get visa type by ID.
     */
    public function getVisaTypeById(int $id): FamilyVisaType
    {
        return FamilyVisaType::with('emirate')->findOrFail($id);
    }

    /**
     * Get visa type by emirate slug and type slug.
     */
    public function getVisaTypeBySlug(string $emirateSlug, string $typeSlug): FamilyVisaType
    {
        return FamilyVisaType::with('emirate')
            ->whereHas('emirate', fn($q) => $q->where('slug', $emirateSlug))
            ->where('slug', $typeSlug)
            ->firstOrFail();
    }

    /**
     * Create a new visa type.
     */
    public function createVisaType(array $data): FamilyVisaType
    {
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = FamilyVisaType::where('emirate_id', $data['emirate_id'])->max('sort_order') + 1;
        }

        return FamilyVisaType::create($data);
    }

    /**
     * Update a visa type.
     */
    public function updateVisaType(FamilyVisaType $type, array $data): FamilyVisaType
    {
        $type->update($data);
        return $type->fresh();
    }

    /**
     * Delete a visa type.
     */
    public function deleteVisaType(FamilyVisaType $type): bool
    {
        return $type->delete();
    }

    /**
     * Reorder visa types.
     */
    public function reorderVisaTypes(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            FamilyVisaType::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    /**
     * Toggle emirate active status.
     */
    public function toggleEmirateActive(FamilyVisaEmirate $emirate): FamilyVisaEmirate
    {
        $emirate->update(['is_active' => !$emirate->is_active]);
        return $emirate->fresh();
    }

    /**
     * Toggle visa type active status.
     */
    public function toggleVisaTypeActive(FamilyVisaType $type): FamilyVisaType
    {
        $type->update(['is_active' => !$type->is_active]);
        return $type->fresh();
    }

    /**
     * Get statistics for family visa data.
     */
    public function getStats(): array
    {
        return [
            'total_emirates' => FamilyVisaEmirate::count(),
            'active_emirates' => FamilyVisaEmirate::active()->count(),
            'total_visa_types' => FamilyVisaType::count(),
            'active_visa_types' => FamilyVisaType::active()->count(),
        ];
    }
}
