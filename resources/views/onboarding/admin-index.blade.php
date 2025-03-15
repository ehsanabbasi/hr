<x-dashboard-layout>
    <x-slot name="title">Onboarding Management</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Onboarding Management</h1>
                <a href="{{ route('onboarding.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create New Task</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($users->isEmpty())
                <div class="bg-gray-100 p-4 rounded-md">
                    <p>No users with onboarding tasks found.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($users as $user)
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                                <h3 class="font-semibold text-lg">{{ $user->name }}</h3>
                                <div>
                                    @php
                                        $totalTasks = count($user->onboardingTasks);
                                        $completedTasks = $user->onboardingTasks->where('status', 'done')->count();
                                        $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                    @endphp
                                    <span class="text-sm text-gray-700">{{ $completedTasks }} of {{ $totalTasks }} tasks completed ({{ round($progressPercentage) }}%)</span>
                                    <a href="{{ route('users.onboarding', $user) }}" class="ml-4 text-blue-600 hover:text-blue-900">View Details</a>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($user->onboardingTasks as $task)
                                        <div class="border rounded p-3 flex justify-between items-center">
                                            <div>
                                                <span class="font-medium">{{ $task->title }}</span>
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                                    {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 
                                                       ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </div>
                                            <a href="{{ route('onboarding.edit', $task) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>