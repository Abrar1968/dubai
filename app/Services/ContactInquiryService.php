<?php

namespace App\Services;

use App\Enums\InquiryStatus;
use App\Models\ContactInquiry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactInquiryService
{
    /**
     * Get all inquiries.
     */
    public function list(?InquiryStatus $status = null): Collection
    {
        $query = ContactInquiry::with('package')->orderBy('created_at', 'desc');

        if ($status) {
            $query->status($status);
        }

        return $query->get();
    }

    /**
     * Get paginated inquiries.
     */
    public function paginate(int $perPage = 15, ?InquiryStatus $status = null): LengthAwarePaginator
    {
        $query = ContactInquiry::with('package')->orderBy('created_at', 'desc');

        if ($status) {
            $query->status($status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get an inquiry by ID.
     */
    public function getById(int $id): ContactInquiry
    {
        return ContactInquiry::with('package')->findOrFail($id);
    }

    /**
     * Create a new inquiry (from contact form).
     */
    public function create(array $data): ContactInquiry
    {
        $data['status'] = InquiryStatus::NEW;

        return ContactInquiry::create($data);
    }

    /**
     * Update an inquiry.
     */
    public function update(ContactInquiry $inquiry, array $data): ContactInquiry
    {
        $inquiry->update($data);

        return $inquiry->fresh();
    }

    /**
     * Delete an inquiry.
     */
    public function delete(ContactInquiry $inquiry): bool
    {
        return $inquiry->delete();
    }

    /**
     * Mark inquiry as read.
     */
    public function markAsRead(ContactInquiry $inquiry): ContactInquiry
    {
        $inquiry->markAsRead();

        return $inquiry->fresh();
    }

    /**
     * Mark inquiry as responded.
     */
    public function markAsResponded(ContactInquiry $inquiry, ?string $notes = null): ContactInquiry
    {
        if ($notes) {
            $inquiry->update(['admin_notes' => $notes]);
        }

        $inquiry->markAsResponded();

        return $inquiry->fresh();
    }

    /**
     * Close an inquiry.
     */
    public function close(ContactInquiry $inquiry): ContactInquiry
    {
        $inquiry->close();

        return $inquiry->fresh();
    }

    /**
     * Get unread inquiries count.
     */
    public function getUnreadCount(): int
    {
        return ContactInquiry::new()->count();
    }

    /**
     * Get recent inquiries.
     */
    public function getRecent(int $limit = 5): Collection
    {
        return ContactInquiry::with('package')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get inquiry statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => ContactInquiry::count(),
            'new' => ContactInquiry::status(InquiryStatus::NEW)->count(),
            'read' => ContactInquiry::status(InquiryStatus::READ)->count(),
            'responded' => ContactInquiry::status(InquiryStatus::RESPONDED)->count(),
            'closed' => ContactInquiry::status(InquiryStatus::CLOSED)->count(),
        ];
    }
}
