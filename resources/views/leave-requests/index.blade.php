<x-dashboard-layout>
    <x-slot name="title">My Leave Requests</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">My Leave Requests</h1>
                <a href="{{ route('leave-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Request Leave</a>
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
                            <th class="py-3 px-6 text-left bg-gray-100">Reason</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Start Date</th>
                            <th class="py-3 px-6 text-left bg-gray-100">End Date</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Duration</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-6">{{ $request->leaveReason->name }}</td>
                                <td class="py-3 px-6">{{ $request->start_date->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-6">{{ $request->end_date->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-6">{{ $request->duration_in_days }} day(s)</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    <a href="{{ route('leave-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    
                                    @if($request->isPending())
                                        <form action="{{ route('leave-requests.cancel', $request) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this leave request?')">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout> 