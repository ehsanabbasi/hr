<x-dashboard-layout>
    <x-slot name="title">Candidates for {{ $job->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Candidates for {{ $job->title }}</h1>
                    <p class="text-gray-600">{{ $job->department->name }} - {{ ucfirst($job->level) }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('jobs.show', $job) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Job</a>
                    @can('createCandidate', $job)
                        <a href="{{ route('jobs.candidates.create', $job) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Candidate</a>
                    @endcan
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
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Name</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Email</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Resume</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidates as $candidate)
                            <tr class="border-b">
                                <td class="py-3 px-6">
                                    <a href="{{ route('jobs.candidates.show', [$job, $candidate]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                        {{ $candidate->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-6">{{ $candidate->email ?? 'N/A' }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $candidate->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                           ($candidate->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($candidate->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    @if($candidate->resume_path)
                                        <a href="{{ route('jobs.candidates.resume', [$job, $candidate]) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                            Download
                                        </a>
                                    @else
                                        <span class="text-gray-500">No resume</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('jobs.candidates.show', [$job, $candidate]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    @can('updateCandidate', $job)
                                        <a href="{{ route('jobs.candidates.edit', [$job, $candidate]) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endcan
                                    @can('deleteCandidate', $job)
                                        <form action="{{ route('jobs.candidates.destroy', [$job, $candidate]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-6 text-center text-gray-500">No candidates found for this job.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $candidates->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>