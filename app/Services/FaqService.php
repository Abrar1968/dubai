<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Collection;

class FaqService
{
    /**
     * Get all FAQs.
     */
    public function list(?string $section = null, bool $activeOnly = false): Collection
    {
        $query = Faq::ordered();

        if ($section) {
            $query->section($section);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get a FAQ by ID.
     */
    public function getById(int $id): Faq
    {
        return Faq::findOrFail($id);
    }

    /**
     * Create a new FAQ.
     */
    public function create(array $data): Faq
    {
        // Set sort order if not provided
        if (!isset($data['sort_order'])) {
            $maxOrder = Faq::where('section', $data['section'] ?? 'global')->max('sort_order');
            $data['sort_order'] = ($maxOrder ?? 0) + 1;
        }

        return Faq::create($data);
    }

    /**
     * Update a FAQ.
     */
    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($data);

        return $faq->fresh();
    }

    /**
     * Delete a FAQ.
     */
    public function delete(Faq $faq): bool
    {
        return $faq->delete();
    }

    /**
     * Reorder FAQs.
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Faq::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    /**
     * Get FAQs for a section (public display).
     */
    public function getForSection(string $section): Collection
    {
        return Faq::forSection($section);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Faq $faq): Faq
    {
        return $this->update($faq, ['is_active' => !$faq->is_active]);
    }

    /**
     * Get FAQ count per section.
     */
    public function getCountBySection(): array
    {
        return [
            'hajj' => Faq::section('hajj')->count(),
            'tour' => Faq::section('tour')->count(),
            'typing' => Faq::section('typing')->count(),
            'global' => Faq::section('global')->count(),
        ];
    }

    /**
     * Get total FAQ count.
     */
    public function getCount(): int
    {
        return Faq::count();
    }
}
