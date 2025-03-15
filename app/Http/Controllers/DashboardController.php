<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkingHour;
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

        return view('dashboard', compact(
            'birthdaysThisWeek',
            'anniversariesThisWeek',
            'hoursWorkedThisMonth',
            'requiredHoursThisMonth'
        ));
    }
} 