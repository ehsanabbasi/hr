<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Certificate::class);
        
        $certificates = Certificate::with('creator')->latest()->paginate(10);
        
        return view('certificates.index', compact('certificates'));
    }
    
    public function create()
    {
        $this->authorize('create', Certificate::class);
        
        $users = User::all();
        
        return view('certificates.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', Certificate::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_mandatory' => 'boolean',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        DB::transaction(function () use ($validated, $request) {
            $certificate = Certificate::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'is_mandatory' => $request->has('is_mandatory'),
                'created_by' => Auth::id(),
            ]);
            
            // Assign to selected users
            foreach ($validated['user_ids'] as $userId) {
                UserCertificate::create([
                    'user_id' => $userId,
                    'certificate_id' => $certificate->id,
                    'status' => 'pending',
                ]);
            }
        });
        
        return redirect()->route('certificates.index')
            ->with('success', 'Certificate created and assigned successfully.');
    }
    
    public function show(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        $certificate->load(['creator', 'userCertificates.user']);
        
        return view('certificates.show', compact('certificate'));
    }
    
    public function edit(Certificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $users = User::all();
        $assignedUserIds = $certificate->userCertificates()->pluck('user_id')->toArray();
        
        return view('certificates.edit', compact('certificate', 'users', 'assignedUserIds'));
    }
    
    public function update(Request $request, Certificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_mandatory' => 'boolean',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        DB::transaction(function () use ($validated, $request, $certificate) {
            $certificate->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'is_mandatory' => $request->has('is_mandatory'),
            ]);
            
            // Get current assigned users
            $currentUserIds = $certificate->userCertificates()->pluck('user_id')->toArray();
            
            // Users to add
            $usersToAdd = array_diff($validated['user_ids'], $currentUserIds);
            
            // Users to remove
            $usersToRemove = array_diff($currentUserIds, $validated['user_ids']);
            
            // Add new users
            foreach ($usersToAdd as $userId) {
                UserCertificate::create([
                    'user_id' => $userId,
                    'certificate_id' => $certificate->id,
                    'status' => 'pending',
                ]);
            }
            
            // Remove users
            if (!empty($usersToRemove)) {
                UserCertificate::where('certificate_id', $certificate->id)
                    ->whereIn('user_id', $usersToRemove)
                    ->delete();
            }
        });
        
        return redirect()->route('certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }
    
    public function destroy(Certificate $certificate)
    {
        $this->authorize('delete', $certificate);
        
        $certificate->delete();
        
        return redirect()->route('certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }
} 