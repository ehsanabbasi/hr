<?php

namespace App\Observers;

use App\Models\OnboardingTask;
use App\Services\NotificationService;

class OnboardingTaskObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the OnboardingTask "created" event.
     */
    public function created(OnboardingTask $task): void
    {
        $this->notificationService->create(
            $task->user,
            $task,
            'onboarding_task',
            'New Onboarding Task Assigned',
            "A new onboarding task has been assigned to you: {$task->title}.",
            'fas fa-tasks'
        );
    }

    /**
     * Handle the OnboardingTask "updated" event.
     */
    public function updated(OnboardingTask $task): void
    {
        if ($task->isDirty('status') && $task->user_id != auth()->id()) {
            $statusText = ucfirst($task->status);
            
            $this->notificationService->create(
                $task->user,
                $task,
                'onboarding_task',
                "Onboarding Task Status Updated",
                "Your onboarding task '{$task->title}' has been updated to: {$statusText}.",
                'fas fa-tasks'
            );
        }
    }
} 