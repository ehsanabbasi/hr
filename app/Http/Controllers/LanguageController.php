<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch the application's language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request)
    {
        $locale = $request->get('locale');

        // Only proceed if user is authenticated
        if (Auth::check() && in_array($locale, config('app.available_locales'))) {
            Auth::user()->setSetting('language', $locale);
            App::setLocale($locale);
            return redirect()->back()->with('status', __('messages.language_updated'));
        }
        
        // If not authenticated, redirect back without changing anything
        return redirect()->back();
    }
} 