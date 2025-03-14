<?php

namespace App\Http\Controllers;

use App\Models\LeaveReason;
use Illuminate\Http\Request;

class LeaveReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveReasons = LeaveReason::paginate(10);
        return view('leave-reasons.index', compact('leaveReasons'));
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
        // Check if the leave reason is being used
        if ($leaveReason->leaveRequests()->exists()) {
            return redirect()->route('leave-reasons.index')
                ->with('error', 'Cannot delete leave reason as it is being used by leave requests.');
        }

        $leaveReason->delete();

        return redirect()->route('leave-reasons.index')
            ->with('success', 'Leave reason deleted successfully.');
    }
}