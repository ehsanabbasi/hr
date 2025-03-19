<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) {
            
            $locale = Auth::user()->getSetting('language', config('app.locale'));
            
            // Ensure the locale is valid
            if (in_array($locale, config('app.available_locales'))) {
                App::setLocale($locale);
            }
        }
        // For guests, just use the default locale from config

        return $next($request);
    }
} 