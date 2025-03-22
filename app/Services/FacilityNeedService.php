<?php

namespace App\Services;

class FacilityNeedService
{
    /**
     * Get all available status options for facility needs.
     *
     * @return array
     */
    public function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'in_review' => 'In Review',
            'accepted' => 'Accepted',
            'delivered' => 'Delivered',
            'rejected' => 'Rejected',
        ];
    }

    /**
     * Get all available priority options for facility needs.
     *
     * @return array
     */
    public function getPriorityOptions(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ];
    }

    /**
     * Get the CSS classes for status badges.
     *
     * @param string $status
     * @return string
     */
    public function getStatusBadgeClasses(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_review' => 'bg-blue-100 text-blue-800',
            'accepted' => 'bg-green-100 text-green-800',
            'delivered' => 'bg-indigo-100 text-indigo-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the CSS classes for priority badges.
     *
     * @param string $priority
     * @return string
     */
    public function getPriorityBadgeClasses(string $priority): string
    {
        return match ($priority) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'high' => 'bg-yellow-100 text-yellow-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
} 