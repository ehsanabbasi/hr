<x-dashboard-layout>
    <x-slot name="title">Add New Career Opportunity</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Add New Career Opportunity</h1>
            
            <form method="POST" action="{{ route('career-opportunities.store') }}">
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
                    <textarea id="description" name="description" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="department_id" class="block mb-2 text-sm font-medium text-gray-700">Department</label>
                    <select id="department_id" name="department_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="level" class="block mb-2 text-sm font-medium text-gray-700">Level</label>
                    <select id="level" name="level" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                    @error('level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="reviewer_ids" class="block mb-2 text-sm font-medium text-gray-700">Reviewers</label>
                    <select id="reviewer_ids" name="reviewer_ids[]" multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('reviewer_ids', [])) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Hold Ctrl (or Cmd) to select multiple reviewers</p>
                    @error('reviewer_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('career-opportunities.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Opportunity
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 