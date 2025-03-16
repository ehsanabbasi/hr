<x-dashboard-layout>
    <x-slot name="title">{{ $job->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $job->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Jobs</a>
                    @can('update', $job)
                        <a href="{{ route('jobs.edit', $job) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Job</a>
                    @endcan
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="font-semibold text-gray-700 mb-2">Department</h2>
                    <p>{{ $job->department->name }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="font-semibold text-gray-700 mb-2">Level</h2>
                    <p>{{ ucfirst($job->level) }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="font-semibold text-gray-700 mb-2">Status</h2>
                    <p>
                        <span class="px-2 py-1 rounded text-xs {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $job->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Description</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold">Candidates</h2>
                    <a href="{{ route('jobs.candidates.index', $job) }}" class="text-blue-600 hover:text-blue-900">View All Candidates</a>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($job->candidates->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Total</h3>
                                <p class="text-2xl">{{ $job->candidates->count() }}</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Accepted</h3>
                                <p class="text-2xl text-green-600">{{ $job->candidates->where('status', 'accepted')->count() }}</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700 mb-1">Rejected</h3>
                                <p class="text-2xl text-red-600">{{ $job->candidates->where('status', 'rejected')->count() }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No candidates have been added to this job yet.</p>
                    @endif
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Reviewers</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($job->reviewers->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($job->reviewers as $reviewer)
                                <li>{{ $reviewer->name }} ({{ $reviewer->email }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No reviewers have been assigned to this job.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>