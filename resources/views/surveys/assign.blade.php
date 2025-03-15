<x-dashboard-layout>
    <x-slot name="title">Assign Survey</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Assign Survey: {{ $survey->title }}</h1>
            
            <form method="POST" action="{{ route('surveys.assign.store', $survey) }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Select Users</label>
                    <div class="mb-2">
                        <div class="flex items-center">
                            <input id="assign-all" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="assign-all" class="ml-2 block text-sm font-medium text-gray-700">Select All Users</label>
                        </div>
                    </div>
                    
                    <div class="border border-gray-300 rounded-md p-4 max-h-96 overflow-y-auto">
                        @foreach($users as $user)
                            <div class="flex items-center mb-2">
                                <input id="user-{{ $user->id }}" type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                       {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="user-{{ $user->id }}" class="ml-2 block text-sm text-gray-700">
                                    {{ $user->name }} ({{ $user->email }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('user_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('surveys.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Assign Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('assign-all');
            const userCheckboxes = document.querySelectorAll('input[name="user_ids[]"]');
            
            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Check if all checkboxes are checked initially
            function updateSelectAllCheckbox() {
                const allChecked = Array.from(userCheckboxes).every(checkbox => checkbox.checked);
                selectAllCheckbox.checked = allChecked;
            }
            
            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllCheckbox);
            });
            
            // Initial check
            updateSelectAllCheckbox();
        });
    </script>
</x-dashboard-layout>