<x-dashboard-layout>
    <x-slot name="title">{{ $user->name }}'s Onboarding</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $user->name }}'s Onboarding Tasks</h1>
                <div class="flex space-x-2">
                    @can('create', App\Models\OnboardingTask::class)
                        <a href="{{ route('onboarding.create', ['user_id' => $user->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Task</a>
                    @endcan
                    <a href="{{ route('users.show', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to User</a>
                </div>
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
                    <p>No onboarding tasks found for this user.</p>
                </div>
            @else
                <div class="mt-4">
                    <ul class="divide-y divide-gray-200 border rounded-lg overflow-hidden">
                        @foreach($tasks as $task)
                            <li class="p-4 bg-white hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $task->title }}</h3>
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                                {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 
                                                   ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </div>
                                        
                                        @if($task->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="text-sm text-gray-500">
                                            @if($task->completed_at)
                                                <span>Completed: {{ $task->completed_at->format('M d, Y') }}</span>
                                            @else
                                                <span>Created: {{ $task->created_at->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            @can('updateStatus', $task)
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
                                            @endcan
                                            
                                            @can('update', $task)
                                                <a href="{{ route('onboarding.edit', $task) }}" class="text-xs px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">
                                                    Edit
                                                </a>
                                            @endcan
                                            
                                            @can('delete', $task)
                                                <form action="{{ route('onboarding.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700" 
                                                            onclick="return confirm('Are you sure you want to delete this task?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>