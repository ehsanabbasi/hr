<?php

namespace App\Observers;

use App\Models\Feedback;
use App\Services\NotificationService;

class FeedbackObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Feedback "created" event.
     */
    public function created(Feedback $feedback): void
    {
        
        $this->notificationService->create(
            $feedback->receiver,
            $feedback,
            'feedback',
            'New Feedback Received',
            "You have received feedback from {$feedback->sender->name}.",
            'fas fa-comment-dots'
        );
    }
} 