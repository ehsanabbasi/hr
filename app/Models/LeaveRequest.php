<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_reason_id',
        'start_date',
        'end_date',
        'note',
        'status',
        'approved_by',
        'rejection_reason',
        'processed_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the leave request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reason for the leave.
     */
    public function leaveReason(): BelongsTo
    {
        return $this->belongsTo(LeaveReason::class);
    }

    /**
     * Get the user who approved/rejected the leave request.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate the duration of the leave in days.
     */
    public function getDurationInDaysAttribute(): float
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if the leave request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the leave request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the leave request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}