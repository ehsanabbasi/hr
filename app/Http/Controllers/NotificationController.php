<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        
        return view('notifications.index', compact('notifications'));
    }
    
    public function show(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark notification as read
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        // Redirect to the related content
        return redirect($notification->getRouteAttribute());
    }
    
    public function markAsRead(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }
        
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
    
    public function getLatestNotifications()
    {
        
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        $count = Auth::user()->notifications()->whereNull('read_at')->count();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }
} 