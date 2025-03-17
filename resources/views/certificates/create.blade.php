<x-dashboard-layout>
    <x-slot name="title">Create Certificate</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Create Certificate</h1>
            
            <form method="POST" action="{{ route('certificates.store') }}">
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
                
                <div class="mb-4">
                    <label for="due_date" class="block mb-2 text-sm font-medium text-gray-700">Due Date</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Mandatory</span>
                    </label>
                    @error('is_mandatory')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Assign to Users</label>
                    <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-2">
                        @foreach($users as $user)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>
                                <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700">{{ $user->name }} ({{ $user->email }})</label>
                            </div>
                        @endforeach
                    </div>
                    @error('user_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('certificates.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 mr-2">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Certificate</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 