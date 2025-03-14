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

        $birthdaysThisWeek = User::whereBetween('birthday', [
            $currentWeekStart->format('m-d'),
            $currentWeekEnd->format('m-d')
        ])->get();

        $anniversariesThisWeek = User::whereBetween('start_date', [
            $currentWeekStart->format('m-d'),
            $currentWeekEnd->format('m-d')
        ])->get();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Fetch working hours for the current month
        $workingHoursThisMonth = WorkingHour::where('user_id', Auth::id())
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->get();

        // Calculate hours worked this month
        $hoursWorkedThisMonth = $workingHoursThisMonth->sum(function ($workingHour) {
            return $workingHour->total_hours;
        });
        // Assuming required hours are stored in a CompanyLaw model
        $companyLaw = \App\Models\CompanyLaw::firstOrCreate();
        $requiredHoursThisMonth = $companyLaw->required_monthly_hours;

        return view('dashboard', compact(
            'birthdaysThisWeek',
            'anniversariesThisWeek',
            'hoursWorkedThisMonth',
            'requiredHoursThisMonth'
        ));
    }
} 