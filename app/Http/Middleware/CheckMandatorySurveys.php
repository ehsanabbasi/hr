<?php

namespace App\Http\Middleware;

use App\Models\Survey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMandatorySurveys
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip check for certain routes
            $excludedRoutes = [
                'surveys.show',
                'surveys.submit',
                'logout',
            ];
            
            if (in_array($request->route()->getName(), $excludedRoutes)) {
                return $next($request);
            }
            
            // Find mandatory surveys that the user hasn't completed
            $mandatorySurvey = Survey::where('is_mandatory', true)
                ->where('is_active', true)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id)
                        ->wherePivot('completed_at', null);
                })
                ->first();
            
            if ($mandatorySurvey) {
                return redirect()->route('surveys.show', $mandatorySurvey)
                    ->with('warning', 'You must complete this mandatory survey before continuing.');
            }
        }
        
        return $next($request);
    }
}