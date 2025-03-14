<x-dashboard-layout>
    <x-slot name="title">Facility Needs</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Facility Needs</h1>
                <a href="{{ route('facility-needs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Request</a>
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
                            <th class="py-3 px-6 text-left bg-gray-100">ID</th>
                            @if(auth()->user()->can('view all facility needs'))
                                <th class="py-3 px-6 text-left bg-gray-100">Requested By</th>
                            @endif
                            <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Priority</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Created</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilityNeeds as $need)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $need->id }}</td>
                                @if(auth()->user()->can('view all facility needs'))
                                    <td class="py-3 px-6">{{ $need->user->name }}</td>
                                @endif
                                <td class="py-3 px-6">{{ $need->title }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $need->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                           ($need->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($need->priority) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $need->status === 'pending' ? 'bg-gray-100 text-gray-800' : 
                                           ($need->status === 'in_review' ? 'bg-blue-100 text-blue-800' : 
                                           ($need->status === 'accepted' ? 'bg-indigo-100 text-indigo-800' : 
                                           ($need->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $need->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">{{ $need->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('facility-needs.show', $need) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    
                                    @if(auth()->id() === $need->user_id && $need->status === 'pending')
                                        <a href="{{ route('facility-needs.edit', $need) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endif
                                    
                                    @if(auth()->id() === $need->user_id && $need->status === 'pending' || auth()->user()->can('delete facility needs'))
                                        <form action="{{ route('facility-needs.destroy', $need) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->can('view all facility needs') ? 7 : 6 }}" class="py-3 px-6 text-center text-gray-500">
                                    No facility needs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $facilityNeeds->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout> 