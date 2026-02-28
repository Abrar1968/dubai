<?php

namespace App\Models;

use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'package_id',
        'service_interested',
        'status',
        'admin_notes',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
            'responded_at' => 'datetime',
        ];
    }

    /**
     * Get the package this inquiry is about.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope to filter new inquiries.
     */
    public function scopeNew($query)
    {
        return $query->where('status', InquiryStatus::NEW);
    }

    /**
     * Scope to filter unread (new) inquiries.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', InquiryStatus::NEW);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, InquiryStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Mark inquiry as read.
     */
    public function markAsRead(): void
    {
        if ($this->status === InquiryStatus::NEW) {
            $this->update(['status' => InquiryStatus::READ]);
        }
    }

    /**
     * Mark inquiry as responded.
     */
    public function markAsResponded(): void
    {
        $this->update([
            'status' => InquiryStatus::RESPONDED,
            'responded_at' => now(),
        ]);
    }

    /**
     * Close the inquiry.
     */
    public function close(): void
    {
        $this->update(['status' => InquiryStatus::CLOSED]);
    }

    /**
     * Check if inquiry is unread.
     */
    public function getIsUnreadAttribute(): bool
    {
        return $this->status === InquiryStatus::NEW;
    }
}
