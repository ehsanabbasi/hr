<x-dashboard-layout>
    <x-slot name="title">My Onboarding</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">My Onboarding Tasks</h1>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <div class="w-1/3 bg-gray-200 rounded-full h-2.5">
                        @php
                            $totalTasks = count($tasks);
                            $completedTasks = $tasks->where('status', 'done')->count();
                            $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-700">{{ $completedTasks }} of {{ $totalTasks }} tasks completed ({{ round($progressPercentage) }}%)</span>
                </div>
            </div>
            
            @if($tasks->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No onboarding tasks assigned to you yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($tasks as $task)
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                                <h3 class="font-semibold text-lg">{{ $task->title }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 
                                       ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-700 mb-4">{{ $task->description }}</p>
                                
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        @if($task->completed_at)
                                            Completed: {{ $task->completed_at->format('M d, Y') }}
                                        @else
                                            Created: {{ $task->created_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <form action="{{ route('onboarding.update-status', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            
                                            @if($task->status === 'ready')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    Start
                                                </button>
                                            @elseif($task->status === 'in_progress')
                                                <input type="hidden" name="status" value="done">
                                                <button type="submit" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                    Complete
                                                </button>
                                            @elseif($task->status === 'done')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="text-xs px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                                                    Reopen
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>