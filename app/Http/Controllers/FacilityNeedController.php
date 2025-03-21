<?php

namespace App\Http\Controllers;

use App\Models\FacilityNeed;
use App\Models\FacilityNeedStatusHistory;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityNeedController extends Controller
{
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');
        
        // Start query with relationships
        $facilityNeedsQuery = FacilityNeed::with(['department', 'requestedBy']);
        
        // Apply search if provided
        if ($search) {
            $facilityNeedsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter if provided
        if ($status) {
            $facilityNeedsQuery->where('status', $status);
        }
        
        // Apply priority filter if provided
        if ($priority) {
            $facilityNeedsQuery->where('priority', $priority);
        }
        
        /* // For non-admin users, show only their department's facility needs
        if (!auth()->user()->can('manage_facility_needs')) {
            $userDepartmentId = auth()->user()->department_id;
            $facilityNeedsQuery->where(function($query) use ($userDepartmentId) {
                $query->where('department_id', $userDepartmentId)
                      ->orWhere('requested_by', auth()->id());
            });
        } */
        
        // Get paginated results
        $facilityNeeds = $facilityNeedsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return view('facility-needs.index', compact('facilityNeeds', 'search', 'status', 'priority'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $priorities = ['low', 'medium', 'high', 'critical'];
        
        return view('facility-needs.create', compact('departments', 'priorities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'priority' => 'required|in:low,medium,high,critical',
            'estimated_cost' => 'nullable|numeric|min:0',
            'desired_completion_date' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['requested_by'] = Auth::id();
        $validated['status'] = 'pending';
        
        FacilityNeed::create($validated);

        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need request submitted successfully.');
    }

    public function show(FacilityNeed $facilityNeed)
    {
        $this->authorize('view', $facilityNeed);
        
        $facilityNeed->load(['department', 'requestedBy', 'approvedBy']);
        return view('facility-needs.show', compact('facilityNeed'));
    }

    public function edit(FacilityNeed $facilityNeed)
    {
        $this->authorize('update', $facilityNeed);
        
        $departments = Department::orderBy('name')->get();
        $priorities = ['low', 'medium', 'high', 'critical'];
        
        return view('facility-needs.edit', compact('facilityNeed', 'departments', 'priorities'));
    }

    public function update(Request $request, FacilityNeed $facilityNeed)
    {
        $this->authorize('update', $facilityNeed);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'priority' => 'required|in:low,medium,high,critical',
            'estimated_cost' => 'nullable|numeric|min:0',
            'desired_completion_date' => 'nullable|date',
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

    public function approve(FacilityNeed $facilityNeed)
    {
        $this->authorize('manage', FacilityNeed::class);
        
        if ($facilityNeed->status !== 'pending') {
            return redirect()->route('facility-needs.index')
                ->with('error', 'Only pending facility needs can be approved.');
        }
        
        $facilityNeed->status = 'approved';
        $facilityNeed->approved_by = Auth::id();
        $facilityNeed->approval_date = now();
        $facilityNeed->save();
        
        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need approved successfully.');
    }
    
    public function deny(FacilityNeed $facilityNeed)
    {
        $this->authorize('manage', FacilityNeed::class);
        
        if ($facilityNeed->status !== 'pending') {
            return redirect()->route('facility-needs.index')
                ->with('error', 'Only pending facility needs can be denied.');
        }
        
        $facilityNeed->status = 'denied';
        $facilityNeed->approved_by = Auth::id();
        $facilityNeed->approval_date = now();
        $facilityNeed->save();
        
        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need denied successfully.');
    }
    
    public function complete(FacilityNeed $facilityNeed)
    {
        $this->authorize('manage', FacilityNeed::class);
        
        if ($facilityNeed->status !== 'approved') {
            return redirect()->route('facility-needs.index')
                ->with('error', 'Only approved facility needs can be marked as completed.');
        }
        
        $facilityNeed->status = 'completed';
        $facilityNeed->completion_date = now();
        $facilityNeed->save();
        
        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need marked as completed successfully.');
    }

    public function destroy(FacilityNeed $facilityNeed)
    {
        $this->authorize('delete', $facilityNeed);
        
        $facilityNeed->delete();

        return redirect()->route('facility-needs.index')
            ->with('success', 'Facility need deleted successfully.');
    }
} 