<x-dashboard-layout>
    <x-slot name="title">Certificate Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Certificate Details</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('users.certificates.index', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to List</a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Certificate Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Title</span>
                            <span class="block mt-1">{{ $certificate->certificate->title }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Description</span>
                            <span class="block mt-1">{{ $certificate->certificate->description ?? 'No description provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Due Date</span>
                            <span class="block mt-1">
                                {{ $certificate->certificate->due_date ? $certificate->certificate->due_date->format('F d, Y') : 'No due date' }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Mandatory</span>
                            <span class="block mt-1">{{ $certificate->certificate->is_mandatory ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Status Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Status</span>
                            <span class="block mt-1">
                                @if($certificate->status === 'completed')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Completed</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                @endif
                            </span>
                        </div>
                        
                        @if($certificate->completed_at)
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Completed At</span>
                            <span class="block mt-1">{{ $certificate->completed_at->format('F d, Y H:i') }}</span>
                        </div>
                        @endif
                        
                        @if($certificate->file_path)
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">File</span>
                            <span class="block mt-1">
                                <a href="{{ route('users.certificates.download', [$user, $certificate]) }}" class="text-blue-600 hover:underline">
                                    {{ $certificate->file_name }}
                                </a>
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($certificate->status !== 'completed' && auth()->id() === $user->id)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold mb-4">Upload Certificate</h2>
                    <form method="POST" action="{{ route('users.certificates.upload', [$user, $certificate]) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="certificate_file" class="block mb-2 text-sm font-medium text-gray-700">Certificate File (PDF or JPEG/JPG)</label>
                            <input id="certificate_file" type="file" name="certificate_file" accept=".pdf,.jpeg,.jpg" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Maximum file size: 10MB</p>
                            @error('certificate_file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload Certificate</button>
                        </div>
                    </form>
                </div>
            @endif
            
            @if($certificate->file_path && Str::endsWith(strtolower($certificate->file_name), ['.jpg', '.jpeg']))
                <div class="mt-6">
                    <h2 class="text-lg font-semibold mb-2">Preview</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <img src="{{ route('users.certificates.download', [$user, $certificate]) }}" alt="{{ $certificate->certificate->title }}" class="max-w-full h-auto">
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 