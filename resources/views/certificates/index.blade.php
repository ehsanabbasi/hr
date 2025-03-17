<x-dashboard-layout>
    <x-slot name="title">Certificates</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Certificates</h1>
                <a href="{{ route('certificates.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Certificate</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($certificates->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No certificates found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Due Date</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Mandatory</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Created By</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Assigned Users</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                                <tr class="border-b">
                                    <td class="py-3 px-6">{{ $certificate->title }}</td>
                                    <td class="py-3 px-6">{{ $certificate->due_date ? $certificate->due_date->format('M d, Y') : 'No due date' }}</td>
                                    <td class="py-3 px-6">{{ $certificate->is_mandatory ? 'Yes' : 'No' }}</td>
                                    <td class="py-3 px-6">{{ $certificate->creator->name }}</td>
                                    <td class="py-3 px-6">{{ $certificate->userCertificates->count() }}</td>
                                    <td class="py-3 px-6">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('certificates.show', $certificate) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('certificates.edit', $certificate) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            <form method="POST" action="{{ route('certificates.destroy', $certificate) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 