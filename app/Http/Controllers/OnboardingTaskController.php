<?php

namespace App\Http\Controllers;

use App\Models\OnboardingTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If user is admin, show all tasks grouped by user
        if ($user->hasRole(['admin', 'hr'])) {
            $users = User::with('onboardingTasks')->has('onboardingTasks')->get();
            return view('onboarding.admin-index', compact('users'));
        }
        
        // Regular users only see their own tasks
        $tasks = $user->onboardingTasks()->latest()->get();
        return view('onboarding.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all();
        return view('onboarding.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        OnboardingTask::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $validated['user_id'],
            'created_by' => Auth::id(),
            'status' => 'ready',
        ]);

        return redirect()->route('users.onboarding', $validated['user_id'])
            ->with('success', 'Onboarding task created successfully.');
    }

    public function edit(OnboardingTask $task)
    {
        $this->authorize('update', $task);
        
        return view('onboarding.edit', compact('task'));
    }

    public function update(Request $request, OnboardingTask $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('users.onboarding', $task->user_id)
            ->with('success', 'Onboarding task updated successfully.');
    }

    public function updateStatus(Request $request, OnboardingTask $task)
    {
        $this->authorize('updateStatus', $task);
        
        $validated = $request->validate([
            'status' => 'required|in:ready,in_progress,done',
        ]);

        $task->status = $validated['status'];
        
        // If status is done, set completed_at
        if ($validated['status'] === 'done' && !$task->completed_at) {
            $task->completed_at = now();
        } elseif ($validated['status'] !== 'done') {
            $task->completed_at = null;
        }
        
        $task->save();

        return redirect()->back()
            ->with('success', 'Task status updated successfully.');
    }

    public function destroy(OnboardingTask $task)
    {
        $this->authorize('delete', $task);
        
        $userId = $task->user_id;
        $task->delete();

        return redirect()->route('users.onboarding', $userId)
            ->with('success', 'Onboarding task deleted successfully.');
    }
    
    public function userOnboarding(User $user)
    {
        $this->authorize('viewOnboarding', $user);
        
        $tasks = $user->onboardingTasks()->latest()->get();
        return view('onboarding.user', compact('user', 'tasks'));
    }
}