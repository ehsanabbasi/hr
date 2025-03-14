<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $receivedFeedbacks = Feedback::where('receiver_id', Auth::id())
            ->with('sender')
            ->latest()
            ->paginate(10, ['*'], 'received');
            
        $sentFeedbacks = Feedback::where('sender_id', Auth::id())
            ->with('receiver')
            ->latest()
            ->paginate(10, ['*'], 'sent');
            
        return view('feedbacks.index', compact('receivedFeedbacks', 'sentFeedbacks'));
    }
    
    public function create(User $user)
    {
        return view('feedbacks.create', compact('user'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'session' => 'nullable|string|max:255',
        ]);
        
        $feedback = Feedback::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'session' => $validated['session'],
        ]);
        
        return redirect()->route('feedbacks.show', $feedback)
            ->with('success', 'Feedback sent successfully!');
    }
    
    public function show(Feedback $feedback)
    {
        // Ensure only sender and receiver can view the feedback
        if (Auth::id() != $feedback->sender_id && Auth::id() != $feedback->receiver_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark as read if viewing as receiver
        if (Auth::id() == $feedback->receiver_id && !$feedback->read_at) {
            $feedback->update(['read_at' => now()]);
        }
        
        return view('feedbacks.show', compact('feedback'));
    }
    
    public function destroy(Feedback $feedback)
    {
        // Ensure only sender can delete the feedback
        if (Auth::id() != $feedback->sender_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $feedback->delete();
        
        return redirect()->route('feedbacks.index')
            ->with('success', 'Feedback deleted successfully!');
    }
} 