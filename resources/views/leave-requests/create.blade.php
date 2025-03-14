<x-dashboard-layout>
    <x-slot name="title">Request Leave</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Request Leave</h1>
            
            <form method="POST" action="{{ route('leave-requests.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="leave_reason_id" class="block mb-2 text-sm font-medium text-gray-700">Leave Reason</label>
                    <select id="leave_reason_id" name="leave_reason_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Reason</option>
                        @foreach($leaveReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('leave_reason_id') == $reason->id ? 'selected' : '' }}>
                                {{ $reason->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('leave_reason_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date & Time</label>
                    <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">End Date & Time</label>
                    <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="note" class="block mb-2 text-sm font-medium text-gray-700">Note</label>
                    <textarea id="note" name="note" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('leave-requests.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 