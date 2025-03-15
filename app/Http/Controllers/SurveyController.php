<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get surveys assigned to the user
        $assignedSurveys = $user->surveys()
            ->with('questions')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // If user is admin, get all surveys
        if ($user->hasRole(['admin', 'hr'])) {
            $allSurveys = Survey::with('questions')
                ->latest()
                ->paginate(10);
                
            return view('surveys.index', compact('assignedSurveys', 'allSurveys'));
        }
        
        return view('surveys.index', compact('assignedSurveys'));
    }

    public function create()
    {
        $users = User::all();
        return view('surveys.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:single_choice,multiple_choice,open_ended,rating,dropdown,date_time',
            'questions.*.is_required' => 'boolean',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $survey = Survey::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_mandatory' => $request->has('is_mandatory'),
                'is_active' => $request->has('is_active'),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            // Create questions
            foreach ($validated['questions'] as $index => $questionData) {
                SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'is_required' => isset($questionData['is_required']),
                    'display_order' => $index + 1,
                    'options' => $questionData['options'] ?? null,
                ]);
            }

            // Assign users
            $survey->users()->attach($validated['users']);
        });

        return redirect()->route('surveys.index')
            ->with('success', 'Survey created successfully.');
    }

    public function show(Survey $survey)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this survey or is admin
        if (!$user->hasRole(['admin', 'hr']) && !$survey->users->contains($user)) {
            abort(403, 'You are not authorized to view this survey.');
        }
        
        $survey->load('questions');
        $userResponses = SurveyResponse::where('survey_id', $survey->id)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('survey_question_id');
            
        $hasCompleted = $survey->hasUserCompleted($user);
        
        return view('surveys.show', compact('survey', 'userResponses', 'hasCompleted'));
    }

    public function edit(Survey $survey)
    {
        $survey->load('questions', 'users');
        $allUsers = User::all();
        
        return view('surveys.edit', compact('survey', 'allUsers'));
    }

    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:survey_questions,id',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:single_choice,multiple_choice,open_ended,rating,dropdown,date_time',
            'questions.*.is_required' => 'boolean',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($survey, $validated, $request) {
            $survey->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_mandatory' => $request->has('is_mandatory'),
                'is_active' => $request->has('is_active'),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            // Delete existing questions
            $survey->questions()->delete();

            // Create new questions
            foreach ($validated['questions'] as $index => $questionData) {
                SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'is_required' => isset($questionData['is_required']),
                    'display_order' => $index + 1,
                    'options' => $questionData['options'] ?? null,
                ]);
            }

            // Sync users
            $survey->users()->sync($validated['users']);
        });

        return redirect()->route('surveys.index')
            ->with('success', 'Survey updated successfully.');
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('surveys.index')
            ->with('success', 'Survey deleted successfully.');
    }

    public function submit(Request $request, Survey $survey)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this survey
        if (!$survey->users->contains($user)) {
            abort(403, 'You are not authorized to submit this survey.');
        }
        
        // Check if survey is active
        if (!$survey->isActive()) {
            return redirect()->route('surveys.show', $survey)
                ->with('error', 'This survey is no longer active.');
        }
        
        // Check if user has already completed the survey
        if ($survey->hasUserCompleted($user)) {
            return redirect()->route('surveys.show', $survey)
                ->with('error', 'You have already completed this survey.');
        }
        
        $survey->load('questions');
        
        // Validate responses
        $rules = [];
        $messages = [];
        
        foreach ($survey->questions as $question) {
            if ($question->is_required) {
                $rules["responses.{$question->id}"] = 'required';
                $messages["responses.{$question->id}.required"] = "The question '{$question->question_text}' is required.";
            }
            
            if ($question->question_type === 'multiple_choice') {
                $rules["responses.{$question->id}"] = 'array';
                $rules["responses.{$question->id}.*"] = 'string';
            }
        }
        
        $validated = $request->validate($rules, $messages);
        
        DB::transaction(function () use ($survey, $user, $request) {
            // Delete any existing responses
            SurveyResponse::where('survey_id', $survey->id)
                ->where('user_id', $user->id)
                ->delete();
            
            // Save new responses
            foreach ($request->input('responses', []) as $questionId => $response) {
                // For multiple choice, convert array to JSON
                if (is_array($response)) {
                    $response = json_encode($response);
                }
                
                SurveyResponse::create([
                    'survey_id' => $survey->id,
                    'survey_question_id' => $questionId,
                    'user_id' => $user->id,
                    'response_value' => $response,
                ]);
            }
            
            // Mark survey as completed for this user
            $survey->users()->updateExistingPivot($user->id, [
                'completed_at' => now(),
            ]);
        });
        
        return redirect()->route('surveys.index')
            ->with('success', 'Survey submitted successfully.');
    }

    public function results(Survey $survey)
    {
        $user = Auth::user();
        
        // Only admin, HR, or users who completed the survey can see results
        if (!$user->hasRole(['admin', 'hr']) && !$survey->hasUserCompleted($user)) {
            abort(403, 'You are not authorized to view these results.');
        }
        
        $survey->load(['questions', 'responses.user']);
        
        // Organize responses by question
        $responsesByQuestion = [];
        
        foreach ($survey->questions as $question) {
            $responses = $survey->responses()->where('survey_question_id', $question->id)->get();
            $responsesByQuestion[$question->id] = $responses;
        }
        
        return view('surveys.results', compact('survey', 'responsesByQuestion'));
    }

        public function assign(Survey $survey)
        {
            $users = User::all();
            $assignedUserIds = $survey->users()->pluck('users.id')->toArray();
            
            return view('surveys.assign', compact('survey', 'users', 'assignedUserIds'));
        }

        public function assignStore(Request $request, Survey $survey)
        {
            $validated = $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
            ]);
            
            // Sync the users with the survey
            $survey->users()->sync($validated['user_ids']);
            
            return redirect()->route('surveys.index')
                ->with('success', 'Survey assigned successfully.');
        }
}