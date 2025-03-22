<x-dashboard-layout>
    <x-slot name="title">Polls</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Polls</h1>
                @can('create polls')
                <a href="{{ route('polls.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create New Poll</a>
                @endcan
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-6">
                <form action="{{ route('polls.index') }}" method="GET" class="flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Search polls..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="h-10 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                        
                        @if(!empty($search ?? request('search')))
                            <a href="{{ route('polls.index') }}" class="h-10 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mandatory</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($polls as $poll)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $poll->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($poll->isActive())
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($poll->is_mandatory)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $poll->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                        <a href="{{ route('polls.show', $poll) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('polls.results', $poll) }}" class="text-green-600 hover:text-green-900">Results</a>
                                        @can('create polls')
                                        <a href="{{ route('polls.edit', $poll) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form method="POST" action="{{ route('polls.destroy', $poll) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this poll?')">Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No polls found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($polls) && $polls->hasPages())
                    <div class="mt-4">
                        {{ $polls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout> 