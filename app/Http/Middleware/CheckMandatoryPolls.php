<?php

namespace App\Http\Middleware;

use App\Models\Poll;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMandatoryPolls
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip check for certain routes
            $excludedRoutes = [
                'polls.respond',
                'polls.show',
                'logout',
            ];
            
            if (in_array($request->route()->getName(), $excludedRoutes)) {
                return $next($request);
            }
            
            // Find mandatory polls that the user hasn't responded to
            $mandatoryPoll = Poll::where('is_mandatory', true)
                ->where('is_active', true)
                ->whereDoesntHave('responses', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();
            
            if ($mandatoryPoll) {
                return redirect()->route('polls.show', $mandatoryPoll)
                    ->with('warning', 'You must complete this mandatory poll before continuing.');
            }
        }
        
        return $next($request);
    }
} 