<?php

namespace App\Services;

use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TestimonialService
{
    /**
     * Get all testimonials.
     */
    public function list(?bool $approvedOnly = null): Collection
    {
        $query = Testimonial::with('package')->orderBy('created_at', 'desc');

        if ($approvedOnly === true) {
            $query->approved();
        } elseif ($approvedOnly === false) {
            $query->pending();
        }

        return $query->get();
    }

    /**
     * Get paginated testimonials.
     */
    public function paginate(int $perPage = 15, ?bool $approvedOnly = null): LengthAwarePaginator
    {
        $query = Testimonial::with('package')->orderBy('created_at', 'desc');

        if ($approvedOnly === true) {
            $query->approved();
        } elseif ($approvedOnly === false) {
            $query->pending();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a testimonial by ID.
     */
    public function getById(int $id): Testimonial
    {
        return Testimonial::with('package')->findOrFail($id);
    }

    /**
     * Create a new testimonial.
     */
    public function create(array $data): Testimonial
    {
        return Testimonial::create($data);
    }

    /**
     * Update a testimonial.
     */
    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);

        return $testimonial->fresh();
    }

    /**
     * Delete a testimonial.
     */
    public function delete(Testimonial $testimonial): bool
    {
        return $testimonial->delete();
    }

    /**
     * Approve a testimonial.
     */
    public function approve(Testimonial $testimonial): Testimonial
    {
        return $this->update($testimonial, ['is_approved' => true]);
    }

    /**
     * Reject (unapprove) a testimonial.
     */
    public function reject(Testimonial $testimonial): Testimonial
    {
        return $this->update($testimonial, ['is_approved' => false]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Testimonial $testimonial): Testimonial
    {
        return $this->update($testimonial, ['is_featured' => !$testimonial->is_featured]);
    }

    /**
     * Get featured testimonials for public display.
     */
    public function getFeatured(int $limit = 6): Collection
    {
        return Testimonial::approved()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get approved testimonials for public display.
     */
    public function getApproved(int $limit = 10): Collection
    {
        return Testimonial::approved()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get testimonial statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Testimonial::count(),
            'approved' => Testimonial::approved()->count(),
            'pending' => Testimonial::pending()->count(),
            'featured' => Testimonial::featured()->count(),
            'average_rating' => round(Testimonial::approved()->avg('rating') ?? 0, 1),
        ];
    }
}
