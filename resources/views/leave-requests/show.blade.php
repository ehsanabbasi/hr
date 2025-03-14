<x-dashboard-layout>
    <x-slot name="title">Leave Request Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Leave Request Details</h1>
                <a href="{{ route('leave-requests.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to List</a>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Leave Reason</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->leaveReason->name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 py-1 rounded text-xs 
                                {{ $leaveRequest->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($leaveRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($leaveRequest->status) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Start Date & Time</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->start_date->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">End Date & Time</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->end_date->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Duration</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->duration_in_days }} day(s)</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Submitted On</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    @if($leaveRequest->status !== 'pending')
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Processed By</h3>
                            <p class="mt-1 text-lg">{{ $leaveRequest->approver->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Processed On</h3>
                            <p class="mt-1 text-lg">{{ $leaveRequest->processed_at ? $leaveRequest->processed_at->format('M d, Y H:i') : 'N/A' }}</p>
                        </div>
                    @endif
                </div>
                
                @if($leaveRequest->note)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Note</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->note }}</p>
                    </div>
                @endif
                
                @if($leaveRequest->isRejected() && $leaveRequest->rejection_reason)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Rejection Reason</h3>
                        <p class="mt-1 text-lg text-red-600">{{ $leaveRequest->rejection_reason }}</p>
                    </div>
                @endif
            </div>
            
            @if($leaveRequest->isPending())
                <div class="flex justify-end">
                    <form action="{{ route('leave-requests.cancel', $leaveRequest) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" 
                                onclick="return confirm('Are you sure you want to cancel this leave request?')">
                            Cancel Request
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 