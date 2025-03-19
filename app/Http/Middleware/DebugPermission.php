<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();
        
        Log::info('Permission check', [
            'user' => $user->id,
            'permission' => $permission,
            'has_permission' => $user->hasPermissionTo($permission),
            'route' => $request->route()->getName()
        ]);
        
        if (!$user->hasPermissionTo($permission)) {
            Log::warning('Permission denied', [
                'user' => $user->id,
                'permission' => $permission
            ]);
            
            // Uncomment to see the error but still access the page
            // return response('Permission debugging: You don\'t have the required permission: ' . $permission, 403);
        }
        
        return $next($request);
    }
}
