<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\InvitationSent; // We will create this later
use Illuminate\Support\Facades\Redirect; // Added for redirect
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config; // Added for Config facade
use Illuminate\Support\Facades\Auth; // Added for Auth facade
use Illuminate\Auth\Events\Registered; // Added for Registered event

class InvitationController extends Controller
{
    /**
     * Show the form for creating a new invitation.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // Return the admin view for sending invitations
        return view('admin.invitations.create'); // Return view directly
    }

    /**
     * Store a newly created invitation in storage and send email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $companyDomain = Config::get('app.company_domain');
        if (!$companyDomain) {
             return Redirect::route('admin.invitations.create')
                   ->withErrors(['email' => 'Company domain is not configured.']);
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'), // Check if email exists in users table
                Rule::unique('invitations', 'email')->whereNull('registered_at'), // Check if pending invitation exists
                function ($attribute, $value, $fail) use ($companyDomain) {
                    if (!str_ends_with(strtolower($value), '@' . strtolower($companyDomain))) {
                        $fail("The {$attribute} must belong to the {$companyDomain} domain.");
                    }
                },
            ],
        ]);

        // Generate token
        $token = Invitation::generateToken();

        // Create invitation
        $invitation = Invitation::create([
            'email' => $validated['email'],
            'token' => $token,
        ]);

        // Send email using Mailable
        Mail::to($invitation->email)->send(new InvitationSent($invitation));

        return Redirect::route('admin.invitations.create')
               ->with('status', 'Invitation sent successfully to ' . $invitation->email);
    }

    /**
     * Show the registration form for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegistrationForm(string $token): View | RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->whereNull('registered_at')->first();

        if (!$invitation) {
            return Redirect::route('login')
                   ->withErrors(['token' => 'Invalid or expired invitation token.']);
        }

        return view('auth.invitation-register', [
            'token' => $token,
            'email' => $invitation->email
        ]);
    }

    /**
     * Handle the registration attempt for an invited user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerInvitedUser(Request $request): RedirectResponse
    {
        $companyDomain = Config::get('app.company_domain');
        if (!$companyDomain) {
             return Redirect::route('login')
                   ->withErrors(['email' => 'Company domain configuration is missing.']);
        }

        $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                function ($attribute, $value, $fail) use ($companyDomain) {
                    if (!str_ends_with(strtolower($value), '@' . strtolower($companyDomain))) {
                        $fail("The {$attribute} must belong to the {$companyDomain} domain.");
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find the invitation
        $invitation = Invitation::where('token', $request->token)
                                ->whereNull('registered_at')
                                ->first();

        // Validate token and email match
        if (!$invitation || strtolower($invitation->email) !== strtolower($request->email)) {
            return Redirect::route('login')
                   ->withErrors(['email' => 'Invalid invitation token or email mismatch.']);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Optionally mark as verified immediately
        ]);

        // Mark invitation as registered
        $invitation->registered_at = now();
        $invitation->save();

        // Fire registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to dashboard
        return Redirect::route('dashboard')
               ->with('status', 'Registration successful! Welcome.');
    }
}
