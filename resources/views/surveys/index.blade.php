<x-dashboard-layout>
    <x-slot name="title">Surveys</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Surveys</h1>
                @can('create surveys')
                <a href="{{ route('surveys.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create New Survey</a>
                @endcan
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <h2 class="text-xl font-semibold mb-4">Your Assigned Surveys</h2>
            <div class="overflow-x-auto mb-8">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Mandatory</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Completion</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignedSurveys as $survey)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-6">{{ $survey->title }}</td>
                                <td class="py-3 px-6">
                                    @if($survey->isActive())
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if($survey->is_mandatory)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if($survey->hasUserCompleted(Auth::user()))
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('surveys.show', $survey) }}" class="text-blue-600 hover:text-blue-900">
                                        @if($survey->hasUserCompleted(Auth::user()))
                                            View
                                        @else
                                            Take Survey
                                        @endif
                                    </a>
                                    @if($survey->hasUserCompleted(Auth::user()))
                                        <a href="{{ route('surveys.results', $survey) }}" class="text-green-600 hover:text-green-900">Results</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 px-6 text-center">No surveys assigned to you.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($allSurveys) && Auth::user()->hasRole(['admin', 'hr']))
                <h2 class="text-xl font-semibold mb-4">All Surveys</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Mandatory</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Questions</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Created</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSurveys as $survey)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-6">{{ $survey->title }}</td>
                                    <td class="py-3 px-6">
                                        @if($survey->isActive())
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($survey->is_mandatory)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">{{ $survey->questions->count() }}</td>
                                    <td class="py-3 px-6">{{ $survey->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-6 flex space-x-2">
                                        <a href="{{ route('surveys.show', $survey) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('surveys.results', $survey) }}" class="text-green-600 hover:text-green-900">Results</a>
                                        <a href="{{ route('surveys.edit', $survey) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form method="POST" action="{{ route('surveys.destroy', $survey) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this survey?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-3 px-6 text-center">No surveys found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $allSurveys->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>