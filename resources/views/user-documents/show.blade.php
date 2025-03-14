<x-dashboard-layout>
    <x-slot name="title">Document Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $document->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('users.documents.download', [$user, $document]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Download</a>
                    <a href="{{ route('users.documents.index', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back</a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Document Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Document Type</span>
                            <span class="block mt-1">{{ $document->documentType->name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Description</span>
                            <span class="block mt-1">{{ $document->description ?: 'No description provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">File Type</span>
                            <span class="block mt-1">{{ $document->file_type }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">File Size</span>
                            <span class="block mt-1">{{ number_format($document->file_size / 1024, 2) }} KB</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Upload Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Uploaded By</span>
                            <span class="block mt-1">{{ $document->uploader->name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Upload Date</span>
                            <span class="block mt-1">{{ $document->created_at->format('F d, Y H:i') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">For User</span>
                            <span class="block mt-1">{{ $document->user->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                <div class="mt-6">
                    <h2 class="text-lg font-semibold mb-2">Preview</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <img src="{{ route('users.documents.download', [$user, $document]) }}" alt="{{ $document->title }}" class="max-w-full h-auto">
                    </div>
                </div>
            @endif
            
            @can('delete', $document)
                <div class="mt-6 border-t pt-4">
                    <form action="{{ route('users.documents.destroy', [$user, $document]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this document?')">Delete Document</button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-dashboard-layout> 