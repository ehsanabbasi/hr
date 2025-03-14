<x-dashboard-layout>
    <x-slot name="title">Facility Need Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $facilityNeed->title }}</h1>
                <div class="flex space-x-2">
                    @if(auth()->id() === $facilityNeed->user_id && $facilityNeed->status === 'pending')
                        <a href="{{ route('facility-needs.edit', $facilityNeed) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                    @endif
                    <a href="{{ route('facility-needs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back</a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">ID</h2>
                    <p class="text-gray-700">{{ $facilityNeed->id }}</p>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Requested By</h2>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3">
                            {{ substr($facilityNeed->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $facilityNeed->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $facilityNeed->user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Priority</h2>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $facilityNeed->priority === 'high' ? 'bg-red-100 text-red-800' : 
                           ($facilityNeed->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($facilityNeed->priority) }}
                    </span>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Status</h2>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $facilityNeed->status === 'pending' ? 'bg-gray-100 text-gray-800' : 
                           ($facilityNeed->status === 'in_review' ? 'bg-blue-100 text-blue-800' : 
                           ($facilityNeed->status === 'accepted' ? 'bg-indigo-100 text-indigo-800' : 
                           ($facilityNeed->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                        {{ ucfirst(str_replace('_', ' ', $facilityNeed->status)) }}
                    </span>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Created At</h2>
                    <p class="text-gray-700">{{ $facilityNeed->created_at->format('M d, Y H:i') }}</p>
                </div>
                
                @if($facilityNeed->completed_at)
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Completed At</h2>
                        <p class="text-gray-700">{{ $facilityNeed->completed_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Description</h2>
                <p class="text-gray-700">{{ $facilityNeed->description ?? 'No description available.' }}</p>
            </div>
            
            @if($facilityNeed->notes)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Notes</h2>
                    <p class="text-gray-700">{{ $facilityNeed->notes }}</p>
                </div>
            @endif
        </div>
    </div>
    
    @can('updateStatus', $facilityNeed)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h2 class="text-lg font-semibold mb-4">Update Status</h2>
                
                <form action="{{ route('facility-needs.update-status', $facilityNeed) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $facilityNeed->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_review" {{ $facilityNeed->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="accepted" {{ $facilityNeed->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="delivered" {{ $facilityNeed->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="rejected" {{ $facilityNeed->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h2 class="text-lg font-semibold mb-4">Status History</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                            <th class="py-3 px-6 text-left bg-gray-100">User</th>
                            <th class="py-3 px-6 text-left bg-gray-100">From</th>
                            <th class="py-3 px-6 text-left bg-gray-100">To</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statusHistory as $history)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $history->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-6">{{ $history->user->name }}</td>
                                <td class="py-3 px-6">
                                    @if($history->old_status)
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $history->old_status === 'pending' ? 'bg-gray-100 text-gray-800' : 
                                               ($history->old_status === 'in_review' ? 'bg-blue-100 text-blue-800' : 
                                               ($history->old_status === 'accepted' ? 'bg-indigo-100 text-indigo-800' : 
                                               ($history->old_status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $history->old_status)) }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $history->new_status === 'pending' ? 'bg-gray-100 text-gray-800' : 
                                           ($history->new_status === 'in_review' ? 'bg-blue-100 text-blue-800' : 
                                           ($history->new_status === 'accepted' ? 'bg-indigo-100 text-indigo-800' : 
                                           ($history->new_status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">{{ $history->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 px-6 text-center text-gray-500">
                                    No status history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout> 