<?php

namespace App\Http\Controllers\Admin\Typing;

use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Get base query for typing inquiries
     */
    protected function baseQuery()
    {
        return ContactInquiry::where('section', 'typing')->orderBy('created_at', 'desc');
    }

    /**
     * Display a listing of inquiries.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->get('status');
        $dailyFilter = $request->get('daily');
        $dateFilter = $request->get('date');
        $status = $statusFilter ? InquiryStatus::from($statusFilter) : null;

        $query = $this->baseQuery();

        if ($dateFilter) {
            $query->whereDate('created_at', $dateFilter);
        } elseif ($dailyFilter === '1') {
            $query->whereDate('created_at', today());
        }

        if ($status) {
            $query->status($status);
        }

        $inquiries = $query->paginate(20);

        // Get counts for status badges
        $counts = [
            'all' => $this->baseQuery()->count(),
            'daily' => $this->baseQuery()->whereDate('created_at', today())->count(),
            'new' => $this->baseQuery()->where('status', InquiryStatus::NEW)->count(),
            'read' => $this->baseQuery()->where('status', InquiryStatus::READ)->count(),
            'responded' => $this->baseQuery()->where('status', InquiryStatus::RESPONDED)->count(),
            'closed' => $this->baseQuery()->where('status', InquiryStatus::CLOSED)->count(),
        ];

        return view('admin.pages.typing.inquiries.index', [
            'inquiries' => $inquiries,
            'currentStatus' => $status,
            'counts' => $counts,
            'isDaily' => $dailyFilter === '1',
            'selectedDate' => $dateFilter ?? today()->format('Y-m-d'),
            'isDateFiltered' => (bool) $dateFilter,
        ]);
    }

    /**
     * Display the specified inquiry.
     */
    public function show(int $id): View
    {
        $inquiry = ContactInquiry::where('section', 'typing')->findOrFail($id);

        // Mark as read if new
        if ($inquiry->status === InquiryStatus::NEW) {
            $inquiry->markAsRead();
            $inquiry->refresh();
        }

        return view('admin.pages.typing.inquiries.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Mark inquiry as read.
     */
    public function markRead(int $id): RedirectResponse
    {
        $inquiry = ContactInquiry::where('section', 'typing')->findOrFail($id);
        $inquiry->markAsRead();

        return redirect()
            ->back()
            ->with('success', 'Inquiry marked as read.');
    }

    /**
     * Mark inquiry as responded.
     */
    public function markResponded(int $id): RedirectResponse
    {
        $inquiry = ContactInquiry::where('section', 'typing')->findOrFail($id);
        $inquiry->markAsResponded();

        return redirect()
            ->back()
            ->with('success', 'Inquiry marked as responded.');
    }

    /**
     * Close the inquiry.
     */
    public function close(int $id): RedirectResponse
    {
        $inquiry = ContactInquiry::where('section', 'typing')->findOrFail($id);
        $inquiry->close();

        return redirect()
            ->back()
            ->with('success', 'Inquiry closed.');
    }

    /**
     * Remove the specified inquiry.
     */
    public function destroy(int $id): RedirectResponse
    {
        $inquiry = ContactInquiry::where('section', 'typing')->findOrFail($id);
        $inquiry->delete();

        return redirect()
            ->route('admin.typing.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Bulk mark as read.
     */
    public function bulkMarkRead(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $inquiry = ContactInquiry::where('section', 'typing')->find($id);
            if ($inquiry && $inquiry->status === InquiryStatus::NEW) {
                $inquiry->markAsRead();
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
            $inquiry = ContactInquiry::where('section', 'typing')->find($id);
            if ($inquiry) {
                $inquiry->delete();
            }
        }

        return redirect()
            ->back()
            ->with('success', count($ids) . ' inquiries deleted.');
    }
}
