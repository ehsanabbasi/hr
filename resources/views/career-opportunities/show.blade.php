<x-dashboard-layout>
    <x-slot name="title">{{ $careerOpportunity->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $careerOpportunity->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('career-opportunities.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to List</a>
                    
                    @can('viewCandidates', $careerOpportunity)
                        <a href="{{ route('career-opportunities.candidates.index', $careerOpportunity) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">View Candidates</a>
                    @endcan
                    
                    @can('update', $careerOpportunity)
                        <a href="{{ route('career-opportunities.edit', $careerOpportunity) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                    @endcan
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Details</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="font-medium">Department:</span> {{ $careerOpportunity->department->name }}
                        </div>
                        <div class="mb-3">
                            <span class="font-medium">Level:</span> {{ ucfirst($careerOpportunity->level) }}
                        </div>
                        <div class="mb-3">
                            <span class="font-medium">Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ $careerOpportunity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $careerOpportunity->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <span class="font-medium">Created:</span> {{ $careerOpportunity->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Reviewers</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @if($careerOpportunity->reviewers->count() > 0)
                            <ul class="list-disc pl-5">
                                @foreach($careerOpportunity->reviewers as $reviewer)
                                    <li>{{ $reviewer->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">No reviewers assigned.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Description</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($careerOpportunity->description)) !!}
                </div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Candidates Summary</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded-md shadow-sm">
                            <div class="text-2xl font-bold text-blue-600">{{ $careerOpportunity->candidates->count() }}</div>
                            <div class="text-sm text-gray-600">Total Candidates</div>
                        </div>
                        <div class="bg-white p-3 rounded-md shadow-sm">
                            <div class="text-2xl font-bold text-green-600">{{ $careerOpportunity->candidates->where('status', 'accepted')->count() }}</div>
                            <div class="text-sm text-gray-600">Accepted</div>
                        </div>
                        <div class="bg-white p-3 rounded-md shadow-sm">
                            <div class="text-2xl font-bold text-red-600">{{ $careerOpportunity->candidates->where('status', 'rejected')->count() }}</div>
                            <div class="text-sm text-gray-600">Rejected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout> 