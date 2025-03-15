<x-dashboard-layout>
    <x-slot name="title">Edit Onboarding Task</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Edit Onboarding Task</h1>
            </div>
            
            <form method="POST" action="{{ route('onboarding.update', $task) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Current Status</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 
                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('users.onboarding', $task->user_id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>