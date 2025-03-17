<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserCertificateController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('viewAny', [UserCertificate::class, $user]);
        
        $certificates = $user->userCertificates()->with('certificate')->latest()->get();
        
        return view('user-certificates.index', compact('user', 'certificates'));
    }
    
    public function show(User $user, UserCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        $certificate->load('certificate');
        
        return view('user-certificates.show', compact('user', 'certificate'));
    }
    
    public function upload(Request $request, User $user, UserCertificate $certificate)
    {
        $this->authorize('upload', $certificate);
        
        $validated = $request->validate([
            'certificate_file' => 'required|file|mimes:pdf,jpeg,jpg|max:10240', // 10MB max
        ]);
        
        $file = $request->file('certificate_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Store file in private storage
        $filePath = $file->storeAs(
            'user_certificates/' . $user->id,
            $fileName,
            'local' // Using the private local disk
        );
        
        // Delete old file if exists
        if ($certificate->file_path) {
            Storage::disk('local')->delete($certificate->file_path);
        }
        
        $certificate->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'completed_at' => now(),
        ]);
        
        return redirect()->route('users.certificates.index', $user)
            ->with('success', 'Certificate uploaded successfully.');
    }
    
    public function download(User $user, UserCertificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        if (!$certificate->file_path) {
            return back()->with('error', 'No file has been uploaded for this certificate.');
        }
        
        return Storage::disk('local')->download($certificate->file_path, $certificate->file_name);
    }
    
    public function destroy(User $user, UserCertificate $certificate)
    {
        $this->authorize('delete', $certificate);
        
        // Delete the file if it exists
        if ($certificate->file_path) {
            Storage::disk('local')->delete($certificate->file_path);
        }
        
        $certificate->delete();
        
        return redirect()->route('users.certificates.index', $user)
            ->with('success', 'Certificate assignment removed successfully.');
    }
} 