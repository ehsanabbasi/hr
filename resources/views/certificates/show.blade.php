<x-dashboard-layout>
    <x-slot name="title">Certificate Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Certificate Details</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('certificates.edit', $certificate) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Edit</a>
                    <a href="{{ route('certificates.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to List</a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Certificate Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Title</span>
                            <span class="block mt-1">{{ $certificate->title }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Description</span>
                            <span class="block mt-1">{{ $certificate->description ?? 'No description provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Due Date</span>
                            <span class="block mt-1">{{ $certificate->due_date ? $certificate->due_date->format('F d, Y') : 'No due date' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Mandatory</span>
                            <span class="block mt-1">{{ $certificate->is_mandatory ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Creation Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Created By</span>
                            <span class="block mt-1">{{ $certificate->creator->name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Created At</span>
                            <span class="block mt-1">{{ $certificate->created_at->format('F d, Y H:i') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Last Updated</span>
                            <span class="block mt-1">{{ $certificate->updated_at->format('F d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Assigned Users</h2>
                
                @if($certificate->userCertificates->isEmpty())
                    <div class="bg-gray-100 p-4 rounded-md">
                        <p>No users have been assigned to this certificate.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">User</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Completed At</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certificate->userCertificates as $userCertificate)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">{{ $userCertificate->user->name }}</td>
                                        <td class="py-3 px-6">
                                            @if($userCertificate->status === 'completed')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            {{ $userCertificate->completed_at ? $userCertificate->completed_at->format('F d, Y H:i') : 'Not completed' }}
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('users.certificates.show', [$userCertificate->user, $userCertificate]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                @if($userCertificate->file_path)
                                                    <a href="{{ route('users.certificates.download', [$userCertificate->user, $userCertificate]) }}" class="text-green-600 hover:text-green-900">Download</a>
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
    </div>
</x-dashboard-layout> 