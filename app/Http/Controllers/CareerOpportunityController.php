<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\CareerOpportunity;
use App\Models\User;
use Illuminate\Http\Request;

class CareerOpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departmentId = $request->input('department_id');
        
        $query = CareerOpportunity::with('department');
        
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        $careerOpportunities = $query->latest()->paginate(10);
        $departments = Department::all();
        
        return view('career-opportunities.index', compact('careerOpportunities', 'departments', 'departmentId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $users = User::all();
        $levels = ['internship', 'junior', 'mid-level', 'senior', 'lead', 'head', 'chief officer'];
        
        return view('career-opportunities.create', compact('departments', 'users', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'level' => 'required|in:internship,junior,mid-level,senior,lead,head,chief officer',
            'reviewer_ids' => 'nullable|array',
            'reviewer_ids.*' => 'exists:users,id',
        ]);

        $careerOpportunity = CareerOpportunity::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'department_id' => $validated['department_id'],
            'level' => $validated['level'],
        ]);

        if (isset($validated['reviewer_ids'])) {
            $careerOpportunity->reviewers()->attach($validated['reviewer_ids']);
        }

        return redirect()->route('career-opportunities.index')
            ->with('success', 'Career opportunity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CareerOpportunity $careerOpportunity)
    {
        $careerOpportunity->load(['department', 'reviewers', 'candidates']);
        return view('career-opportunities.show', compact('careerOpportunity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerOpportunity $careerOpportunity)
    {
        $departments = Department::all();
        $users = User::all();
        $levels = ['internship', 'junior', 'mid-level', 'senior', 'lead', 'head', 'chief officer'];
        $careerOpportunity->load('reviewers');
        
        return view('career-opportunities.edit', compact('careerOpportunity', 'departments', 'users', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CareerOpportunity $careerOpportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'level' => 'required|in:internship,junior,mid-level,senior,lead,head,chief officer',
            'is_active' => 'boolean',
            'reviewer_ids' => 'nullable|array',
            'reviewer_ids.*' => 'exists:users,id',
        ]);

        $careerOpportunity->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'department_id' => $validated['department_id'],
            'level' => $validated['level'],
            'is_active' => $request->has('is_active'),
        ]);

        if (isset($validated['reviewer_ids'])) {
            $careerOpportunity->reviewers()->sync($validated['reviewer_ids']);
        } else {
            $careerOpportunity->reviewers()->detach();
        }

        return redirect()->route('career-opportunities.index')
            ->with('success', 'Career opportunity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CareerOpportunity $careerOpportunity)
    {
        $careerOpportunity->delete();

        return redirect()->route('career-opportunities.index')
            ->with('success', 'Career opportunity deleted successfully.');
    }
}
