<?php

namespace App\Http\Controllers;

use App\Models\WorkingHour;
use App\Models\User;
use App\Models\CompanyLaw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class WorkingHourController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $workingHours = WorkingHour::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(30);

        return view('working-hours.index', compact('workingHours'));
    }

    public function create()
    {
        return view('working-hours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i|after:break_start_time',
        ]);

        $validated['user_id'] = Auth::id();

        $validated = $this->validateWorkingHours($validated);

        $workingHour = WorkingHour::create($validated);

        return redirect()->route('working-hours.index')
            ->with('success', 'Working hours added successfully.');
    }

    public function edit(WorkingHour $workingHour)
    {
        $this->authorize('update', $workingHour);

        return view('working-hours.edit', compact('workingHour'));
    }

    public function update(Request $request, WorkingHour $workingHour)
    {
        $this->authorize('update', $workingHour);

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i|after:break_start_time',
        ]);

        $validated['user_id'] = Auth::id();

        $validated = $this->validateWorkingHours($validated);

        $workingHour->update($validated);

        return redirect()->route('working-hours.index')
            ->with('success', 'Working hours updated successfully.');
    }

    public function destroy(WorkingHour $workingHour)
    {
        $this->authorize('delete', $workingHour);

        $workingHour->delete();

        return redirect()->route('working-hours.index')
            ->with('success', 'Working hours deleted successfully.');
    }

    public function showMonthly()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->format('Y-m');
        $workingHours = WorkingHour::where('user_id', $user->id)
            ->where('date', 'like', $currentMonth . '%')
            ->orderBy('date', 'asc')
            ->get();

        $totalHours = $workingHours->sum('total_hours');

        return view('working-hours.monthly', compact('workingHours', 'totalHours'));
    }

    protected function validateWorkingHours(array $data)
    {
        $companyLaw = CompanyLaw::firstOrCreate();
        $user = Auth::user();
        $date = Carbon::parse($data['date']);

        // Calculate total hours
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);
        $totalHours = $endTime->diffInHours($startTime);

        // Calculate break hours
        $breakHours = 0;
        if (isset($data['break_start_time']) && isset($data['break_end_time'])) {
            $breakStartTime = Carbon::parse($data['break_start_time']);
            $breakEndTime = Carbon::parse($data['break_end_time']);
            $breakHours = $breakEndTime->diffInHours($breakStartTime);
        }

        // Net working hours (total - break)
        $netHours = $totalHours - $breakHours;

        // Check daily hours
        if ($totalHours > $companyLaw->max_daily_hours) {
            throw ValidationException::withMessages([
                'end_time' => 'Total working hours for the day exceed the maximum allowed daily hours.',
            ]);
        }

        // Check daily break hours
        if ($breakHours > $companyLaw->max_daily_break_hours) {
            throw ValidationException::withMessages([
                'break_end_time' => 'Total break hours for the day exceed the maximum allowed daily break hours.',
            ]);
        }

        // Calculate weekly hours manually instead of using sum
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        $weeklyEntries = WorkingHour::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();
        
        $weeklyHours = $weeklyEntries->sum(function($entry) {
            $startTime = Carbon::parse($entry->start_time);
            $endTime = Carbon::parse($entry->end_time);
            $totalHours = $endTime->diffInHours($startTime);
            
            $breakHours = 0;
            if ($entry->break_start_time && $entry->break_end_time) {
                $breakStartTime = Carbon::parse($entry->break_start_time);
                $breakEndTime = Carbon::parse($entry->break_end_time);
                $breakHours = $breakEndTime->diffInHours($breakStartTime);
            }
            
            return $totalHours - $breakHours;
        });
        
        if ($weeklyHours + $netHours > $companyLaw->max_weekly_hours) {
            throw ValidationException::withMessages([
                'end_time' => 'Total working hours for the week exceed the maximum allowed weekly hours.',
            ]);
        }

        // Similarly, calculate monthly hours manually
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        $monthlyEntries = WorkingHour::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
        
        $monthlyHours = $monthlyEntries->sum(function($entry) {
            $startTime = Carbon::parse($entry->start_time);
            $endTime = Carbon::parse($entry->end_time);
            $totalHours = $endTime->diffInHours($startTime);
            
            $breakHours = 0;
            if ($entry->break_start_time && $entry->break_end_time) {
                $breakStartTime = Carbon::parse($entry->break_start_time);
                $breakEndTime = Carbon::parse($entry->break_end_time);
                $breakHours = $breakEndTime->diffInHours($breakStartTime);
            }
            
            return $totalHours - $breakHours;
        });
        
        if ($monthlyHours + $netHours > $companyLaw->max_monthly_hours) {
            throw ValidationException::withMessages([
                'end_time' => 'Total working hours for the month exceed the maximum allowed monthly hours.',
            ]);
        }

        // Calculate day of week
        $dayOfWeek = Carbon::parse($data['date'])->format('l'); // Returns the full day name (Monday, Tuesday, etc.)

        return array_merge($data, [
            'total_hours' => $netHours,
            'day_of_week' => strtolower($dayOfWeek) // Store in lowercase to match your view's ucfirst()
        ]);
    }
} 