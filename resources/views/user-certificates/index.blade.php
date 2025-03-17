<x-dashboard-layout>
    <x-slot name="title">{{ $user->name }}'s Certificates</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $user->name }}'s Certificates</h1>
                <a href="{{ route('users.show', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to User</a>
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
            
            @if($certificates->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No certificates found for this user.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Due Date</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Mandatory</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                                <tr class="border-b">
                                    <td class="py-3 px-6">{{ $certificate->certificate->title }}</td>
                                    <td class="py-3 px-6">
                                        {{ $certificate->certificate->due_date ? $certificate->certificate->due_date->format('M d, Y') : 'No due date' }}
                                    </td>
                                    <td class="py-3 px-6">{{ $certificate->certificate->is_mandatory ? 'Yes' : 'No' }}</td>
                                    <td class="py-3 px-6">
                                        @if($certificate->status === 'completed')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('users.certificates.show', [$user, $certificate]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            @if($certificate->file_path)
                                                <a href="{{ route('users.certificates.download', [$user, $certificate]) }}" class="text-green-600 hover:text-green-900">Download</a>
                                            @endif
                                        </div>
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