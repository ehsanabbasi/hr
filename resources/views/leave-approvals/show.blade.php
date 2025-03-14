<x-dashboard-layout>
    <x-slot name="title">Review Leave Request</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Review Leave Request</h1>
                <a href="{{ route('leave-approvals.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to List</a>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Employee</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->user->name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Department</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->user->department->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Job Title</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->user->jobTitle->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Leave Reason</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->leaveReason->name }}</p>
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
                </div>
                
                @if($leaveRequest->note)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Note</h3>
                        <p class="mt-1 text-lg">{{ $leaveRequest->note }}</p>
                    </div>
                @endif
            </div>
            
            <form method="POST" action="{{ route('leave-approvals.process', $leaveRequest) }}" id="approvalForm">
                @csrf
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Decision</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="action" value="approve" class="form-radio" checked>
                            <span class="ml-2">Approve</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="action" value="reject" class="form-radio" id="rejectRadio">
                            <span class="ml-2">Reject</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-6 hidden" id="rejectionReasonDiv">
                    <label for="rejection_reason" class="block mb-2 text-sm font-medium text-gray-700">Rejection Reason</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('rejection_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" id="approveBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve
                    </button>
                    <button type="button" id="rejectBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const rejectRadio = document.getElementById('rejectRadio');
                    const rejectionReasonDiv = document.getElementById('rejectionReasonDiv');
                    const approveBtn = document.getElementById('approveBtn');
                    const rejectBtn = document.getElementById('rejectBtn');
                    const approvalForm = document.getElementById('approvalForm');
                    
                    // Toggle rejection reason field visibility
                    rejectRadio.addEventListener('change', function() {
                        rejectionReasonDiv.classList.remove('hidden');
                    });
                    
                    document.querySelector('input[name="action"][value="approve"]').addEventListener('change', function() {
                        rejectionReasonDiv.classList.add('hidden');
                    });
                    
                    // Handle approve button click
                    approveBtn.addEventListener('click', function() {
                        document.querySelector('input[name="action"][value="approve"]').checked = true;
                        rejectionReasonDiv.classList.add('hidden');
                        if (confirm('Are you sure you want to approve this leave request?')) {
                            approvalForm.submit();
                        }
                    });
                    
                    // Handle reject button click
                    rejectBtn.addEventListener('click', function() {
                        document.querySelector('input[name="action"][value="reject"]').checked = true;
                        rejectionReasonDiv.classList.remove('hidden');
                        
                        const rejectionReason = document.getElementById('rejection_reason').value.trim();
                        if (rejectionReason === '') {
                            alert('Please provide a reason for rejection.');
                            document.getElementById('rejection_reason').focus();
                            return;
                        }
                        
                        if (confirm('Are you sure you want to reject this leave request?')) {
                            approvalForm.submit();
                        }
                    });
                });
            </script>
        </div>
    </div>
</x-dashboard-layout> 