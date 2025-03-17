<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function create(
        User $user,
        Model $notifiable,
        string $type,
        string $title,
        string $content,
        string $icon
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'icon' => $icon,
        ]);
    }
} 