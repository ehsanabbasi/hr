<?php

namespace App\Http\Controllers;

use App\Models\LeaveReason;
use Illuminate\Http\Request;

class LeaveReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->input('search');
        $status = $request->input('status');
        
        // Start query
        $leaveReasonsQuery = LeaveReason::query();
        
        // Apply search if provided
        if ($search) {
            $leaveReasonsQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Apply status filter if provided
        if ($status !== null && $status !== '') {
            $leaveReasonsQuery->where('active', $status);
        }
        
        // Get paginated results
        $leaveReasons = $leaveReasonsQuery
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
        
        return view('leave-reasons.index', compact('leaveReasons', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave-reasons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        LeaveReason::create($validated);

        return redirect()->route('leave-reasons.index')
            ->with('success', 'Leave reason created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveReason $leaveReason)
    {
        return view('leave-reasons.edit', compact('leaveReason'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveReason $leaveReason)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $leaveReason->update($validated);

        return redirect()->route('leave-reasons.index')
            ->with('success', 'Leave reason updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveReason $leaveReason)
    {
        try {
            $leaveReason->delete();
            return redirect()->route('leave-reasons.index')
                ->with('success', 'Leave reason deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('leave-reasons.index')
                ->with('error', 'Unable to delete this leave reason. It may be in use.');
        }
    }
}