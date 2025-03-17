<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'notifiable_type',
        'notifiable_id',
        'icon',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    public function getRouteAttribute(): string
    {
        // Define routes based on notification type
        switch ($this->type) {
            case 'feedback':
                return route('feedbacks.show', $this->notifiable_id);
            case 'facility_need':
                return route('facility-needs.show', $this->notifiable_id);
            case 'onboarding_task':
                return route('onboarding.index');
            default:
                return route('dashboard');
        }
    }
    
    public function getIconClassAttribute(): string
    {
        if (!empty($this->icon)) {
            return $this->icon;
        }
        
        // Default icons based on type
        switch ($this->type) {
            case 'feedback':
                return 'fas fa-comment-dots';
            case 'facility_need':
                return 'fas fa-tools';
            case 'onboarding_task':
                return 'fas fa-tasks';
            case 'leave_request':
                return 'fas fa-calendar-alt';
            default:
                return 'fas fa-bell';
        }
    }
} 