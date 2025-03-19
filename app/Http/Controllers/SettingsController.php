<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Language settings data only
        return view('settings');
    }
    
    public function saveLanguage(Request $request)
    {
        // Validate the request
        $request->validate([
            'locale' => 'required|string|in:en,fr,de', // Add any other supported languages
        ]);
        
        // Get authenticated user
        $user = auth()->user();
        
        // Save the language preference
        $user->setSetting('language', $request->locale);
        
        // Set flash message
        session()->flash('status', 'Language settings updated successfully!');
        
        // Redirect back to settings
        return redirect()->route('settings');
    }
} 