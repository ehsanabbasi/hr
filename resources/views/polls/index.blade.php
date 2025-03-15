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
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Mandatory</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Created</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($polls as $poll)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-6">{{ $poll->title }}</td>
                                <td class="py-3 px-6">
                                    @if($poll->isActive())
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    @if($poll->is_mandatory)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Mandatory</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Optional</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6">{{ $poll->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-6 flex space-x-2">
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
                                <td colspan="5" class="py-3 px-6 text-center">No polls found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $polls->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout> 