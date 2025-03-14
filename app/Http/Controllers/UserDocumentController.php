<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserDocumentController extends Controller
{
    public function index(User $user)
    {
        if (Gate::denies('viewAny', [UserDocument::class, $user])) {
            abort(403, 'Unauthorized action.');
        }
        
        $documents = $user->documents()->with(['documentType', 'uploader'])->latest()->get();
        
        return view('user-documents.index', compact('user', 'documents'));
    }
    
    public function create(User $user)
    {
        if (Gate::denies('create', [UserDocument::class, $user])) {
            abort(403, 'Unauthorized action.');
        }
        
        $documentTypes = DocumentType::where('active', true)->get();
        
        return view('user-documents.create', compact('user', 'documentTypes'));
    }
    
    public function store(Request $request, User $user)
    {
        if (Gate::denies('create', [UserDocument::class, $user])) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type_id' => 'required|exists:document_types,id',
            'document_file' => 'required|file|mimes:pdf,xml,docx,jpeg,jpg,png|max:10240', // 10MB max
        ]);
        
        $file = $request->file('document_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Store file in private storage
        $filePath = $file->storeAs(
            'user_documents/' . $user->id,
            $fileName,
            'local' // Using the private local disk
        );
        
        UserDocument::create([
            'user_id' => $user->id,
            'uploaded_by' => Auth::id(),
            'document_type_id' => $validated['document_type_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
        
        return redirect()->route('users.documents.index', $user)
            ->with('success', 'Document uploaded successfully.');
    }
    
    public function show(User $user, UserDocument $document)
    {
        if (Gate::denies('view', $document)) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user-documents.show', compact('user', 'document'));
    }
    
    public function download(User $user, UserDocument $document)
    {
        if (Gate::denies('view', $document)) {
            abort(403, 'Unauthorized action.');
        }
        
        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }
    
    public function destroy(User $user, UserDocument $document)
    {
        if (Gate::denies('delete', $document)) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete the file from storage
        Storage::disk('local')->delete($document->file_path);
        
        // Delete the record
        $document->delete();
        
        return redirect()->route('users.documents.index', $user)
            ->with('success', 'Document deleted successfully.');
    }
}
