<x-dashboard-layout>
    <x-slot name="title">{{ $user->name }}'s Documents</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $user->name }}'s Documents</h1>
                <div class="flex space-x-2">
                    @can('create', [App\Models\UserDocument::class, $user])
                        <a href="{{ route('users.documents.create', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload Document</a>
                    @endcan
                    <a href="{{ route('users.show', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to User</a>
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
            
            @if($documents->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No documents found for this user.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Type</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Uploaded By</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr class="border-b">
                                    <td class="py-3 px-6">
                                        <a href="{{ route('users.documents.show', [$user, $document]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                            {{ $document->title }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-6">{{ $document->documentType->name }}</td>
                                    <td class="py-3 px-6">{{ $document->uploader->name }}</td>
                                    <td class="py-3 px-6">{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-3 px-6 flex space-x-2">
                                        <a href="{{ route('users.documents.download', [$user, $document]) }}" class="text-green-600 hover:text-green-900">Download</a>
                                        
                                        @can('delete', $document)
                                            <form action="{{ route('users.documents.destroy', [$user, $document]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 