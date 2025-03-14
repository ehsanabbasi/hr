<x-dashboard-layout>
    <x-slot name="title">Pending Leave Approvals</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Pending Leave Approvals</h1>
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
            
            @if($pendingRequests->isEmpty())
                <div class="bg-blue-50 p-4 rounded-md">
                    <p class="text-blue-700">There are no pending leave requests to approve.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-3 px-6 text-left bg-gray-100">Employee</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Department</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Reason</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Start Date</th>
                                <th class="py-3 px-6 text-left bg-gray-100">End Date</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Duration</th>
                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $request)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-6">{{ $request->user->name }}</td>
                                    <td class="py-3 px-6">{{ $request->user->department->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-6">{{ $request->leaveReason->name }}</td>
                                    <td class="py-3 px-6">{{ $request->start_date->format('M d, Y H:i') }}</td>
                                    <td class="py-3 px-6">{{ $request->end_date->format('M d, Y H:i') }}</td>
                                    <td class="py-3 px-6">{{ $request->duration_in_days }} day(s)</td>
                                    <td class="py-3 px-6">
                                        <a href="{{ route('leave-approvals.show', $request) }}" class="text-blue-600 hover:text-blue-900">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $pendingRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 