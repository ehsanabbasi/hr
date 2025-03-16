<x-dashboard-layout>
    <x-slot name="title">Candidates for {{ $careerOpportunity->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Candidates for {{ $careerOpportunity->title }}</h1>
                    <p class="text-gray-600">{{ $careerOpportunity->department->name }} - {{ ucfirst($careerOpportunity->level) }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('career-opportunities.show', $careerOpportunity) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Opportunity</a>
                    @can('createCandidate', $careerOpportunity)
                        <a href="{{ route('career-opportunities.candidates.create', $careerOpportunity) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Candidate</a>
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
                            <th class="py-3 px-6 text-left bg-gray-100">Phone</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Resume</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidates as $candidate)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $candidate->name }}</td>
                                <td class="py-3 px-6">{{ $candidate->email }}</td>
                                <td class="py-3 px-6">{{ $candidate->phone }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $candidate->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                           ($candidate->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($candidate->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    @if($candidate->resume_path)
                                        <a href="{{ route('career-opportunities.candidates.resume', [$careerOpportunity, $candidate]) }}" class="text-blue-600 hover:text-blue-900">
                                            Download
                                        </a>
                                    @else
                                        <span class="text-gray-400">No resume</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('career-opportunities.candidates.show', [$careerOpportunity, $candidate]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    
                                    @can('updateCandidate', $careerOpportunity)
                                        <a href="{{ route('career-opportunities.candidates.edit', [$careerOpportunity, $candidate]) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endcan
                                    
                                    @can('deleteCandidate', $careerOpportunity)
                                        <form action="{{ route('career-opportunities.candidates.destroy', [$careerOpportunity, $candidate]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-3 px-6 text-center">No candidates found for this opportunity.</td>
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