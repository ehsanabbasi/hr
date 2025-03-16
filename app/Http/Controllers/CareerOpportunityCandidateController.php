<?php

namespace App\Http\Controllers;

use App\Models\CareerOpportunity;
use App\Models\CareerOpportunityCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerOpportunityCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CareerOpportunity $careerOpportunity)
    {
        $this->authorize('viewCandidates', $careerOpportunity);
        
        $candidates = $careerOpportunity->candidates()->latest()->paginate(10);
        return view('career-opportunity-candidates.index', compact('careerOpportunity', 'candidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CareerOpportunity $careerOpportunity)
    {
        $this->authorize('createCandidate', $careerOpportunity);
        
        return view('career-opportunity-candidates.create', compact('careerOpportunity'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CareerOpportunity $careerOpportunity)
    {
        $this->authorize('createCandidate', $careerOpportunity);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'nullable|file|mimes:pdf|max:10240',
            'notes' => 'nullable|string',
        ]);

        $candidateData = [
            'career_opportunity_id' => $careerOpportunity->id,
            'name' => $validated['name'],
            'gender' => $validated['gender'] ?? null,
            'birthday' => $validated['birthday'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $candidateData['resume_path'] = $path;
        }

        CareerOpportunityCandidate::create($candidateData);

        return redirect()->route('career-opportunities.candidates.index', $careerOpportunity)
            ->with('success', 'Candidate added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CareerOpportunity $careerOpportunity, CareerOpportunityCandidate $candidate)
    {
        $this->authorize('viewCandidates', $careerOpportunity);
        
        return view('career-opportunity-candidates.show', compact('careerOpportunity', 'candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerOpportunity $careerOpportunity, CareerOpportunityCandidate $candidate)
    {
        $this->authorize('updateCandidate', $careerOpportunity);
        
        return view('career-opportunity-candidates.edit', compact('careerOpportunity', 'candidate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CareerOpportunity $careerOpportunity, CareerOpportunityCandidate $candidate)
    {
        $this->authorize('updateCandidate', $careerOpportunity);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:not-checked,rejected,accepted',
            'notes' => 'nullable|string',
        ]);

        $candidateData = [
            'name' => $validated['name'],
            'gender' => $validated['gender'] ?? null,
            'birthday' => $validated['birthday'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];

        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($candidate->resume_path) {
                Storage::disk('public')->delete($candidate->resume_path);
            }
            
            $path = $request->file('resume')->store('resumes', 'public');
            $candidateData['resume_path'] = $path;
        }

        $candidate->update($candidateData);

        return redirect()->route('career-opportunities.candidates.index', $careerOpportunity)
            ->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CareerOpportunity $careerOpportunity, CareerOpportunityCandidate $candidate)
    {
        $this->authorize('deleteCandidate', $careerOpportunity);
        
        // Delete resume file if exists
        if ($candidate->resume_path) {
            Storage::disk('public')->delete($candidate->resume_path);
        }
        
        $candidate->delete();

        return redirect()->route('career-opportunities.candidates.index', $careerOpportunity)
            ->with('success', 'Candidate deleted successfully.');
    }

    /**
     * Download the candidate's resume.
     */
    public function downloadResume(CareerOpportunity $careerOpportunity, CareerOpportunityCandidate $candidate)
    {
        $this->authorize('viewCandidates', $careerOpportunity);
        
        if (!$candidate->resume_path) {
            return back()->with('error', 'No resume available for this candidate.');
        }
        
        return Storage::disk('public')->download($candidate->resume_path, $candidate->name . '_resume.pdf');
    }
}
