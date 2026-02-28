<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'section',
        'assigned_by',
    ];

    /**
     * Get the admin user this section belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who assigned this section.
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope to filter by section.
     */
    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }
}
