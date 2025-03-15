<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::with('options')->latest()->paginate(10);
        return view('polls.index', compact('polls'));
    }

    public function create()
    {
        return view('polls.create');
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
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $poll = Poll::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_mandatory' => $validated['is_mandatory'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            foreach ($validated['options'] as $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => $optionText,
                ]);
            }
        });

        return redirect()->route('polls.index')
            ->with('success', 'Poll created successfully.');
    }

    public function show(Poll $poll)
    {
        $poll->load(['options.responses', 'responses']);
        $userHasResponded = $poll->hasUserResponded(Auth::user());
        
        return view('polls.show', compact('poll', 'userHasResponded'));
    }

    public function edit(Poll $poll)
    {
        $poll->load('options');
        return view('polls.edit', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($poll, $validated) {
            $poll->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_mandatory' => $validated['is_mandatory'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            // Delete existing options
            $poll->options()->delete();

            // Create new options
            foreach ($validated['options'] as $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => $optionText,
                ]);
            }
        });

        return redirect()->route('polls.index')
            ->with('success', 'Poll updated successfully.');
    }

    public function destroy(Poll $poll)
    {
        $poll->delete();

        return redirect()->route('polls.index')
            ->with('success', 'Poll deleted successfully.');
    }

    public function respond(Request $request, Poll $poll)
    {
        $validated = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        // Check if user has already responded
        if ($poll->hasUserResponded(Auth::user())) {
            return redirect()->route('polls.show', $poll)
                ->with('error', 'You have already responded to this poll.');
        }

        // Create response
        PollResponse::create([
            'user_id' => Auth::id(),
            'poll_id' => $poll->id,
            'poll_option_id' => $validated['poll_option_id'],
        ]);

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Your response has been recorded.');
    }

    public function results(Poll $poll)
    {
        $poll->load(['options.responses', 'responses.user']);
        
        return view('polls.results', compact('poll'));
    }
} 