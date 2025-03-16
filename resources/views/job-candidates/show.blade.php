<x-dashboard-layout>
    <x-slot name="title">{{ $candidate->name }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">{{ $candidate->name }}</h1>
                    <p class="text-gray-600">Candidate for {{ $job->title }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('jobs.candidates.index', $job) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Candidates</a>
                    @can('updateCandidate', $job)
                        <a href="{{ route('jobs.candidates.edit', [$job, $candidate]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Candidate</a>
                    @endcan
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Personal Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Email</span>
                            <span class="block mt-1">{{ $candidate->email ?? 'Not provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Phone</span>
                            <span class="block mt-1">{{ $candidate->phone ?? 'Not provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Gender</span>
                            <span class="block mt-1">{{ $candidate->gender ? ucfirst($candidate->gender) : 'Not provided' }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Birthday</span>
                            <span class="block mt-1">{{ $candidate->birthday ? $candidate->birthday->format('M d, Y') : 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Application Status</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Status</span>
                            <span class="block mt-1">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $candidate->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                       ($candidate->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($candidate->status) }}
                                </span>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Applied For</span>
                            <span class="block mt-1">{{ $job->title }} ({{ ucfirst($job->level) }})</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Department</span>
                            <span class="block mt-1">{{ $job->department->name }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Resume</span>
                            <span class="block mt-1">
                                @if($candidate->resume_path)
                                    <a href="{{ route('jobs.candidates.resume', [$job, $candidate]) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                        Download Resume
                                    </a>
                                @else
                                    <span class="text-gray-500">No resume uploaded</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($candidate->notes)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Notes</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        {!! nl2br(e($candidate->notes)) !!}
                    </div>
                </div>
            @endif
            
            @can('updateCandidate', $job)
                <div class="mt-6 border-t pt-6">
                    <h2 class="text-lg font-semibold mb-4">Update Status</h2>
                    <form action="{{ route('jobs.candidates.update', [$job, $candidate]) }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $candidate->name }}">
                        <input type="hidden" name="gender" value="{{ $candidate->gender }}">
                        <input type="hidden" name="birthday" value="{{ $candidate->birthday ? $candidate->birthday->format('Y-m-d') : '' }}">
                        <input type="hidden" name="email" value="{{ $candidate->email }}">
                        <input type="hidden" name="phone" value="{{ $candidate->phone }}">
                        <input type="hidden" name="notes" value="{{ $candidate->notes }}">
                        
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="not-checked" {{ $candidate->status === 'not-checked' ? 'selected' : '' }}>Not Checked</option>
                            <option value="accepted" {{ $candidate->status === 'accepted' ? 'selected' : '' }}>Accept</option>
                            <option value="rejected" {{ $candidate->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                        
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-dashboard-layout>