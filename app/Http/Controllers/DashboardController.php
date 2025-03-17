<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkingHour;
use App\Models\Survey;
use App\Models\Poll;
use App\Models\Certificate;
use App\Models\OnboardingTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $birthdaysThisWeek = User::whereMonth('birthday', $currentWeekStart->month)
            ->whereDay('birthday', '>=', $currentWeekStart->day)
            ->whereDay('birthday', '<=', $currentWeekEnd->day)
        ->get();

        $anniversariesThisWeek = User::whereMonth('start_date', $currentWeekStart->month)
            ->whereDay('start_date', '>=', $currentWeekStart->day)
            ->whereDay('start_date', '<=', $currentWeekEnd->day)
        ->get();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Fetch working hours for the current month
        $workingHoursThisMonth = WorkingHour::where('user_id', Auth::id())
            ->whereMonth('date', $currentMonthStart->month)
            ->whereDay('date', '>=', $currentMonthStart->day)
            ->whereDay('date', '<=', $currentMonthEnd->day)
            ->get();

        // Calculate hours worked this month
        $hoursWorkedThisMonth = $workingHoursThisMonth->sum(function ($workingHour) {
            return $workingHour->total_hours;
        });
        
        $companyLaw = \App\Models\CompanyLaw::first();  
        $requiredHoursThisMonth = $companyLaw->min_monthly_hours;

        // Get mandatory items data
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'hr']);
        
        // Mandatory surveys
        if ($isAdmin) {
            $mandatorySurveys = Survey::where('is_mandatory', true)
                ->where('is_active', true)
                ->with(['users' => function($query) {
                    $query->wherePivot('completed_at', null);
                }])
                ->get()
                ->filter(function($survey) {
                    return $survey->users->isNotEmpty();
                });
        } else {
            $mandatorySurveys = Survey::where('is_mandatory', true)
                ->where('is_active', true)
                ->whereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id)
                        ->wherePivot('completed_at', null);
                })
                ->get();
        }
        
        // Mandatory polls
        if ($isAdmin) {
            $mandatoryPolls = Poll::where('is_mandatory', true)
                ->where('is_active', true)
                ->with('responses')
                ->get();
                
            // Get users who haven't responded to each poll
            $pollsWithUsers = [];
            foreach ($mandatoryPolls as $poll) {
                $respondedUserIds = $poll->responses->pluck('user_id')->unique();
                $nonRespondedUsers = User::whereNotIn('id', $respondedUserIds)->get();
                
                if ($nonRespondedUsers->isNotEmpty()) {
                    $pollsWithUsers[] = [
                        'poll' => $poll,
                        'users' => $nonRespondedUsers
                    ];
                }
            }
            $mandatoryPolls = $pollsWithUsers;
        } else {
            $mandatoryPolls = Poll::where('is_mandatory', true)
                ->where('is_active', true)
                ->whereDoesntHave('responses', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();
        }
        
        // Mandatory certificates
        if ($isAdmin) {
            $mandatoryCertificates = Certificate::where('is_mandatory', true)
                ->with(['userCertificates' => function($query) {
                    $query->where('status', 'pending');
                    $query->with('user');
                }])
                ->get()
                ->filter(function($certificate) {
                    return $certificate->userCertificates->isNotEmpty();
                });
        } else {
            $mandatoryCertificates = Certificate::where('is_mandatory', true)
                ->whereHas('userCertificates', function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('status', 'pending');
                })
                ->get();
        }
        
        // Onboarding tasks
        if ($isAdmin) {
            $pendingOnboardingTasks = OnboardingTask::where('status', '!=', 'done')
                ->with('user')
                ->get()
                ->groupBy('user_id');
        } else {
            $pendingOnboardingTasks = $user->onboardingTasks()
                ->where('status', '!=', 'done')
                ->get();
        }

        return view('dashboard', compact(
            'birthdaysThisWeek',
            'anniversariesThisWeek',
            'hoursWorkedThisMonth',
            'requiredHoursThisMonth',
            'mandatorySurveys',
            'mandatoryPolls',
            'mandatoryCertificates',
            'pendingOnboardingTasks',
            'isAdmin'
        ));
    }
} 