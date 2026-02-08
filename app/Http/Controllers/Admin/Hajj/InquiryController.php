<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Enums\InquiryStatus;
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
        $statusFilter = $request->get('status');
        $dailyFilter = $request->get('daily');
        $status = $statusFilter ? InquiryStatus::from($statusFilter) : null;

        $inquiries = $this->inquiryService->paginate(
            perPage: 20,
            status: $status,
            daily: $dailyFilter === '1'
        );

        // Get counts for status badges
        $counts = [
            'all' => $this->inquiryService->list()->count(),
            'daily' => $this->inquiryService->listDaily()->count(),
            'new' => $this->inquiryService->list(InquiryStatus::NEW)->count(),
            'read' => $this->inquiryService->list(InquiryStatus::READ)->count(),
            'responded' => $this->inquiryService->list(InquiryStatus::RESPONDED)->count(),
            'closed' => $this->inquiryService->list(InquiryStatus::CLOSED)->count(),
        ];

        return view('admin.pages.inquiries.index', [
            'inquiries' => $inquiries,
            'currentStatus' => $status,
            'counts' => $counts,
            'isDaily' => $dailyFilter === '1',
        ]);
    }

    /**
     * Display the specified inquiry.
     */
    public function show(int $id): View
    {
        $inquiry = $this->inquiryService->getById($id);

        // Mark as read if new
        if ($inquiry->status === InquiryStatus::NEW) {
            $this->inquiryService->markAsRead($inquiry);
            $inquiry->refresh();
        }

        return view('admin.pages.inquiries.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Mark inquiry as responded.
     */
    public function markResponded(int $id): RedirectResponse
    {
        $inquiry = $this->inquiryService->getById($id);
        $this->inquiryService->markAsResponded($inquiry);

        return redirect()
            ->back()
            ->with('success', 'Inquiry marked as responded.');
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
            if ($inquiry->status === InquiryStatus::NEW) {
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
