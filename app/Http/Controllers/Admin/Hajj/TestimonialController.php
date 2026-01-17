<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Services\MediaService;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(
        protected TestimonialService $testimonialService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $testimonials = $this->testimonialService->paginate(
            perPage: 15,
            filters: $status ? ['status' => $status] : []
        );

        return view('admin.pages.testimonials.index', [
            'testimonials' => $testimonials,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        return view('admin.pages.testimonials.create');
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(TestimonialRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->mediaService->upload(
                $request->file('avatar'),
                'testimonials',
                ['width' => 200, 'height' => 200]
            );
        }

        $this->testimonialService->create($data);

        return redirect()
            ->route('admin.hajj.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(int $id): View
    {
        $testimonial = $this->testimonialService->getById($id);

        return view('admin.pages.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(TestimonialRequest $request, int $id): RedirectResponse
    {
        $testimonial = $this->testimonialService->getById($id);
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($testimonial->avatar) {
                $this->mediaService->delete($testimonial->avatar);
            }
            $data['avatar'] = $this->mediaService->upload(
                $request->file('avatar'),
                'testimonials',
                ['width' => 200, 'height' => 200]
            );
        }

        $this->testimonialService->update($testimonial, $data);

        return redirect()
            ->route('admin.hajj.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(int $id): RedirectResponse
    {
        $testimonial = $this->testimonialService->getById($id);

        // Delete avatar
        if ($testimonial->avatar) {
            $this->mediaService->delete($testimonial->avatar);
        }

        $this->testimonialService->delete($testimonial);

        return redirect()
            ->route('admin.hajj.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    /**
     * Approve a testimonial.
     */
    public function approve(int $id): RedirectResponse
    {
        $testimonial = $this->testimonialService->getById($id);
        $this->testimonialService->approve($testimonial);

        return redirect()
            ->back()
            ->with('success', 'Testimonial approved successfully.');
    }

    /**
     * Reject a testimonial.
     */
    public function reject(int $id): RedirectResponse
    {
        $testimonial = $this->testimonialService->getById($id);
        $this->testimonialService->reject($testimonial);

        return redirect()
            ->back()
            ->with('success', 'Testimonial rejected successfully.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(int $id): RedirectResponse
    {
        $testimonial = $this->testimonialService->getById($id);
        $this->testimonialService->toggleFeatured($testimonial);

        return redirect()
            ->back()
            ->with('success', 'Testimonial featured status updated.');
    }
}
