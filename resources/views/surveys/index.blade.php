<x-dashboard-layout>
    <x-slot name="title">{{ __('Surveys') }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ __('Surveys') }}</h1>
                <div class="space-x-2">
                    @can('create surveys')
                    <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Survey
                    </a>
                    @endcan
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <h2 class="text-xl font-semibold mb-4">Your Assigned Surveys</h2>
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mandatory</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignedSurveys as $survey)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $survey->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($survey->isActive())
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($survey->is_mandatory)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($survey->hasUserCompleted(Auth::user()))
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
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
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No surveys assigned to you.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(isset($allSurveys) && Auth::user()->hasRole(['admin', 'hr']))
                <h2 class="text-xl font-semibold mb-4">All Surveys</h2>
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mandatory</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($allSurveys as $survey)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $survey->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($survey->isActive())
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($survey->is_mandatory)
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                            @else
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $survey->questions->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $survey->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
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
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No surveys found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $allSurveys->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>