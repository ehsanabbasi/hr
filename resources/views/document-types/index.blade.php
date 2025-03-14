<x-dashboard-layout>
    <x-slot name="title">Document Types</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Document Types</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('document-types.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New</a>
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
            
            @if($documentTypes->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No document types found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Name</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Description</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentTypes as $documentType)
                                <tr class="border-b">
                                    <td class="py-3 px-6">{{ $documentType->name }}</td>
                                    <td class="py-3 px-6">{{ $documentType->description }}</td>
                                    <td class="py-3 px-6">
                                        <span class="px-2 py-1 rounded-full text-xs {{ $documentType->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $documentType->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 flex space-x-2">
                                        <a href="{{ route('document-types.edit', $documentType) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('document-types.destroy', $documentType) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this document type?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $documentTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 