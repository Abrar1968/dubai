<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Services\ContactInquiryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function __construct(
        protected ContactInquiryService $inquiryService
    ) {}

    /**
     * Display a listing of inquiries.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $inquiries = $this->inquiryService->paginate(
            perPage: 20,
            filters: $status ? ['status' => $status] : []
        );

        // Get counts for status badges
        $counts = [
            'all' => $this->inquiryService->list()->count(),
            'new' => $this->inquiryService->list(['status' => 'new'])->count(),
            'read' => $this->inquiryService->list(['status' => 'read'])->count(),
            'replied' => $this->inquiryService->list(['status' => 'replied'])->count(),
            'closed' => $this->inquiryService->list(['status' => 'closed'])->count(),
        ];

        return view('admin.pages.inquiries.index', [
            'inquiries' => $inquiries,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    /**
     * Display the specified inquiry.
     */
    public function show(int $id): View
    {
        $inquiry = $this->inquiryService->getById($id);

        // Mark as read if new
        if ($inquiry->status === 'new') {
            $this->inquiryService->markAsRead($inquiry);
            $inquiry->refresh();
        }

        return view('admin.pages.inquiries.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Mark inquiry as replied.
     */
    public function markReplied(int $id): RedirectResponse
    {
        $inquiry = $this->inquiryService->getById($id);
        $this->inquiryService->markAsReplied($inquiry);

        return redirect()
            ->back()
            ->with('success', 'Inquiry marked as replied.');
    }

    /**
     * Close the inquiry.
     */
    public function close(int $id): RedirectResponse
    {
        $inquiry = $this->inquiryService->getById($id);
        $this->inquiryService->close($inquiry);

        return redirect()
            ->back()
            ->with('success', 'Inquiry closed.');
    }

    /**
     * Remove the specified inquiry.
     */
    public function destroy(int $id): RedirectResponse
    {
        $inquiry = $this->inquiryService->getById($id);
        $this->inquiryService->delete($inquiry);

        return redirect()
            ->route('admin.hajj.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Bulk mark as read.
     */
    public function bulkMarkRead(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $inquiry = $this->inquiryService->getById($id);
            if ($inquiry->status === 'new') {
                $this->inquiryService->markAsRead($inquiry);
            }
        }

        return redirect()
            ->back()
            ->with('success', count($ids) . ' inquiries marked as read.');
    }

    /**
     * Bulk delete.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $inquiry = $this->inquiryService->getById($id);
            $this->inquiryService->delete($inquiry);
        }

        return redirect()
            ->back()
            ->with('success', count($ids) . ' inquiries deleted.');
    }
}
