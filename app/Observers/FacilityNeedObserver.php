<?php

namespace App\Observers;

use App\Models\FacilityNeed;
use App\Services\NotificationService;

class FacilityNeedObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the FacilityNeed "updated" event.
     */
    public function updated(FacilityNeed $facilityNeed): void
    {
        if ($facilityNeed->isDirty('status')) {
            $statusText = ucfirst(str_replace('_', ' ', $facilityNeed->status));
            
            $this->notificationService->create(
                $facilityNeed->user,
                $facilityNeed,
                'facility_need',
                "Facility Need Status Updated",
                "Your facility need request '{$facilityNeed->title}' has been updated to: {$statusText}.",
                'fas fa-tools'
            );
        }
    }
} 