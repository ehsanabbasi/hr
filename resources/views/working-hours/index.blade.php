<x-dashboard-layout>
    <x-slot name="title">{{ __('Working Hours') }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ __('Working Hours') }}</h1>
                <div class="space-x-2">
                    <a href="{{ route('working-hours.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Working Hours
                    </a>
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
            
            <!-- Search Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-gray-200 mb-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <!-- Search Form -->
                    <div class="flex-grow">
                        <form action="{{ route('working-hours.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                            <div class="flex-grow">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Search Working Hours
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" 
                                           class="block w-full h-10 pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                           placeholder="Search by employee name" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            
                            <div class="w-full md:w-52">
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select name="department" id="department" class="block w-full h-10 pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm appearance-none">
                                    <option value="">All Departments</option>
                                    @foreach($departments ?? [] as $department)
                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex space-x-2 items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Search
                                </button>
                                <a href="{{ route('working-hours.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Day
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                End Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Break
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Hours
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($workingHours as $workingHour)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $workingHour->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst(\Carbon\Carbon::parse($workingHour->date)->format('l')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('h:i A', strtotime($workingHour->start_time)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('h:i A', strtotime($workingHour->end_time)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($workingHour->break_start_time && $workingHour->break_end_time)
                                        @php
                                            $breakStartTime = \Carbon\Carbon::parse($workingHour->break_start_time);
                                            $breakEndTime = \Carbon\Carbon::parse($workingHour->break_end_time);
                                            $breakHours = $breakStartTime->diffInHours($breakEndTime);
                                            $breakMinutes = $breakStartTime->diffInMinutes($breakEndTime) % 60;
                                        @endphp
                                        {{ date('h:i A', strtotime($workingHour->break_start_time)) }} - 
                                        {{ date('h:i A', strtotime($workingHour->break_end_time)) }}
                                        <span class="text-xs text-gray-500">
                                            (
                                            @if($breakHours >= 1)
                                                {{ $breakHours }}h {{ $breakMinutes }}m
                                            @else
                                                {{ $breakStartTime->diffInMinutes($breakEndTime) }}m
                                            @endif
                                            )
                                        </span>
                                    @else
                                        No break
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $startTime = \Carbon\Carbon::parse($workingHour->start_time);
                                        $endTime = \Carbon\Carbon::parse($workingHour->end_time);
                                        $totalHours = $startTime->diffInHours($endTime);
                                        $totalMinutes = $startTime->diffInMinutes($endTime) % 60;
                                        
                                        $breakHours = 0;
                                        $breakMinutes = 0;
                                        if ($workingHour->break_start_time && $workingHour->break_end_time) {
                                            $breakStartTime = \Carbon\Carbon::parse($workingHour->break_start_time);
                                            $breakEndTime = \Carbon\Carbon::parse($workingHour->break_end_time);
                                            $breakHours = $breakStartTime->diffInHours($breakEndTime);
                                            $breakMinutes = $breakStartTime->diffInMinutes($breakEndTime) % 60;
                                        }
                                        
                                        $netMinutes = ($totalHours * 60 + $totalMinutes) - ($breakHours * 60 + $breakMinutes);
                                        $netHours = floor($netMinutes / 60);
                                        $netMinutes = $netMinutes % 60;
                                    @endphp
                                    <span class="font-semibold">
                                        @if($netHours >= 1)
                                            {{ $netHours }}h {{ $netMinutes }}m
                                        @else
                                            {{ $netMinutes }}m
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('working-hours.edit', $workingHour->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('working-hours.destroy', $workingHour->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this working hours record?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No working hours found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($workingHours->hasPages())
                <div class="mt-4">
                    {{ $workingHours->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 