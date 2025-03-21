<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    public function index(Request $request)
    {
        // Get search parameter
        $search = $request->input('search');
        
        // Start query with department relationship and user count
        $jobTitlesQuery = JobTitle::with('department')
            ->withCount('users');
        
        // Apply search if provided
        if ($search) {
            $jobTitlesQuery->where('name', 'like', "%{$search}%");
        }
        
        // Get paginated results
        $jobTitles = $jobTitlesQuery->paginate(10)->withQueryString();
        
        return view('job-titles.index', compact('jobTitles', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('job-titles.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        JobTitle::create($validated);

        return redirect()->route('job-titles.index')
            ->with('success', 'Job title created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobTitle $jobTitle)
    {
        $jobTitle->load(['department', 'users']);
        return view('job-titles.show', compact('jobTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobTitle $jobTitle)
    {
        $departments = Department::all();
        return view('job-titles.edit', compact('jobTitle', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobTitle $jobTitle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        $jobTitle->update($validated);

        return redirect()->route('job-titles.index')
            ->with('success', 'Job title updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTitle $jobTitle)
    {
        $jobTitle->delete();

        return redirect()->route('job-titles.index')
            ->with('success', 'Job title deleted successfully.');
    }

    /**
     * Get job titles for a specific department.
     */
    public function getByDepartment(Department $department)
    {
        $jobTitles = $department->jobTitles;
        return response()->json($jobTitles);
    }
} 