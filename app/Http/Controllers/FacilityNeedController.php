<?php

namespace App\Http\Controllers;

use App\Models\FacilityNeed;
use App\Models\FacilityNeedStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityNeedController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If user is HR or admin, show all needs
        if ($user->hasRole(['hr', 'admin'])) {
            $facilityNeeds = FacilityNeed::with('user')->latest()->paginate(10);
        } else {
            // Regular users only see their own needs
            $facilityNeeds = FacilityNeed::where('user_id', $user->id)->latest()->paginate(10);
        }
        
        return view('facility-needs.index', compact('facilityNeeds'));
    }

    public function create()
    {
        return view('facility-needs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $facilityNeed = FacilityNeed::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        // Create initial status history
        FacilityNeedStatusHistory::create([
            'facility_need_id' => $facilityNeed->id,
            'user_id' => Auth::id(),
            'old_status' => '',
            'new_status' => 'pending',
            'notes' => 'Initial request created',
        ]);

        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need request submitted successfully.');
    }

    public function show(FacilityNeed $facilityNeed)
    {
        $this->authorize('view', $facilityNeed);
        
        $statusHistory = $facilityNeed->statusHistory;
        
        return view('facility-needs.show', compact('facilityNeed', 'statusHistory'));
    }

    public function edit(FacilityNeed $facilityNeed)
    {
        $this->authorize('update', $facilityNeed);
        
        return view('facility-needs.edit', compact('facilityNeed'));
    }

    public function update(Request $request, FacilityNeed $facilityNeed)
    {
        $this->authorize('update', $facilityNeed);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $facilityNeed->update($validated);

        return redirect()->route('facility-needs.show', $facilityNeed)
            ->with('success', 'Facility need updated successfully.');
    }

    public function updateStatus(Request $request, FacilityNeed $facilityNeed)
    {
        $this->authorize('updateStatus', $facilityNeed);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_review,accepted,delivered,rejected',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $facilityNeed->status;
        $newStatus = $validated['status'];
        
        // Update the facility need status
        $facilityNeed->status = $newStatus;
        
        // If status is delivered, set completed_at
        if ($newStatus === 'delivered' && !$facilityNeed->completed_at) {
            $facilityNeed->completed_at = now();
        }
        
        $facilityNeed->save();
        
        // Create status history entry
        FacilityNeedStatusHistory::create([
            'facility_need_id' => $facilityNeed->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('facility-needs.show', $facilityNeed)
            ->with('success', 'Status updated successfully.');
    }

    public function destroy(FacilityNeed $facilityNeed)
    {
        $this->authorize('delete', $facilityNeed);
        
        $facilityNeed->delete();

        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need deleted successfully.');
    }
} 