<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Get all feedbacks (if any)
        $feedbacks = Feedback::all();
        
        // Notification types
        $notificationTypes = ['feedback', 'system', 'onboarding_task', 'facility_need'];
        
        // Icons for different notification types
        $icons = [
            'feedback' => 'fa-comment',
            'system' => 'fa-bell',
            'onboarding_task' => 'fa-tasks',
            'facility_need' => 'fa-building',
        ];
        
        // Create 50 random notifications
        $notifications = [];
        $now = now();
        
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $type = Arr::random($notificationTypes);
            $createdAt = fake()->dateTimeBetween('-30 days', 'now');
            
            // Determine if notification should be read or unread (70% chance of being read)
            $readAt = fake()->boolean(70) ? fake()->dateTimeBetween($createdAt, 'now') : null;
            
            // Generate notification data based on type
            $notificationData = $this->generateNotificationData($type, $user, $feedbacks);
            
            $notifications[] = [
                'user_id' => $user->id,
                'title' => $notificationData['title'],
                'content' => $notificationData['content'],
                'type' => $type,
                'notifiable_type' => $notificationData['notifiable_type'],
                'notifiable_id' => $notificationData['notifiable_id'],
                'icon' => $icons[$type],
                'read_at' => $readAt,
                'created_at' => $createdAt,
                'updated_at' => $now,
            ];
        }
        
        // Insert all notifications
        DB::table('notifications')->insert($notifications);
        
        $this->command->info('Notifications seeded successfully.');
    }
    
    /**
     * Generate notification data based on type
     */
    private function generateNotificationData(string $type, User $user, $feedbacks): array
    {
        switch ($type) {
            case 'feedback':
                $feedback = $feedbacks->isNotEmpty() ? $feedbacks->random() : null;
                $feedbackId = $feedback ? $feedback->id : rand(1, 100);
                
                return [
                    'title' => 'New Feedback Received',
                    'content' => 'You have received new feedback from ' . fake()->name() . '.',
                    'notifiable_type' => 'App\\Models\\Feedback',
                    'notifiable_id' => $feedbackId,
                ];
                
            case 'system':
                return [
                    'title' => 'System Notification',
                    'content' => Arr::random([
                        'Your password will expire in 7 days. Please update it.',
                        'System maintenance scheduled for this weekend.',
                        'New company policy has been published.',
                        'Welcome to our new HR platform!',
                    ]),
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $user->id,
                ];
                
            case 'onboarding_task':
                return [
                    'title' => 'Onboarding Task Update',
                    'content' => Arr::random([
                        'Your onboarding task "Complete profile information" is due tomorrow.',
                        'HR has approved your onboarding documents.',
                        'Please complete your remaining onboarding tasks.',
                        'New onboarding task assigned: "Security training".',
                    ]),
                    'notifiable_type' => 'App\\Models\\OnboardingTask',
                    'notifiable_id' => rand(1, 100),
                ];
                
            case 'facility_need':
                return [
                    'title' => 'Facility Request Update',
                    'content' => Arr::random([
                        'Your facility request has been approved.',
                        'Maintenance has been scheduled for your reported issue.',
                        'Your desk setup request is in progress.',
                        'IT department has resolved your equipment request.',
                    ]),
                    'notifiable_type' => 'App\\Models\\FacilityNeed',
                    'notifiable_id' => rand(1, 100),
                ];
                
            default:
                return [
                    'title' => 'Notification',
                    'content' => 'You have a new notification.',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $user->id,
                ];
        }
    }
} 