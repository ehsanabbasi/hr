<x-dashboard-layout>
    <x-slot name="title">Create Poll</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Create New Poll</h1>
            
            <form method="POST" action="{{ route('polls.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4 flex space-x-4">
                    <div class="flex items-center">
                        <input id="is_mandatory" type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_mandatory" class="ml-2 block text-sm text-gray-700">Mandatory</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>
                
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date (Optional)</label>
                        <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">End Date (Optional)</label>
                        <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Poll Options</label>
                    <p class="text-sm text-gray-500 mb-2">Add at least two options for your poll.</p>
                    
                    <div id="options-container">
                        <div class="mb-2 flex items-center">
                            <input type="text" name="options[]" required placeholder="Option 1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-2 flex items-center">
                            <input type="text" name="options[]" required placeholder="Option 2"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <button type="button" id="add-option" class="mt-2 px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Add Option
                    </button>
                    
                    @error('options')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('options.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('polls.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Poll
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addOptionButton = document.getElementById('add-option');
            const optionsContainer = document.getElementById('options-container');
            let optionCount = 2;
            
            addOptionButton.addEventListener('click', function() {
                optionCount++;
                const optionDiv = document.createElement('div');
                optionDiv.className = 'mb-2 flex items-center';
                optionDiv.innerHTML = `
                    <input type="text" name="options[]" required placeholder="Option ${optionCount}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" class="ml-2 px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 remove-option">
                        Remove
                    </button>
                `;
                optionsContainer.appendChild(optionDiv);
                
                // Add event listener to the remove button
                optionDiv.querySelector('.remove-option').addEventListener('click', function() {
                    optionDiv.remove();
                });
            });
        });
    </script>
</x-dashboard-layout> 