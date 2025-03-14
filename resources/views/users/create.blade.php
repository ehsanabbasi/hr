<x-dashboard-layout>
    <x-slot name="title">Add New User</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Add New User</h1>
            
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="department_id" class="block mb-2 text-sm font-medium text-gray-700">Department</label>
                    <select id="department_id" name="department_id" 
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
                    <label for="job_title_id" class="block mb-2 text-sm font-medium text-gray-700">Job Title</label>
                    <select id="job_title_id" name="job_title_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Job Title</option>
                        @foreach($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}" 
                                    data-department="{{ $jobTitle->department_id }}"
                                    {{ old('job_title_id') == $jobTitle->id ? 'selected' : '' }}
                                    class="job-title-option">
                                {{ $jobTitle->name }} ({{ $jobTitle->department->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('job_title_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="birthday" class="block mb-2 text-sm font-medium text-gray-700">Birthday</label>
                    <input id="birthday" type="date" name="birthday" value="{{ old('birthday') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('birthday')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date</label>
                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="emergency_contact_name" class="block mb-2 text-sm font-medium text-gray-700">Emergency Contact Name</label>
                    <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('emergency_contact_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="emergency_contact_phone" class="block mb-2 text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                    <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('emergency_contact_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('users.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('department_id');
            const jobTitleSelect = document.getElementById('job_title_id');
            const jobTitleOptions = document.querySelectorAll('.job-title-option');
            
            // Function to filter job titles based on selected department
            function filterJobTitles() {
                const selectedDepartmentId = departmentSelect.value;
                
                // Reset job title select
                jobTitleSelect.value = '';
                
                // Show/hide job title options based on department
                jobTitleOptions.forEach(option => {
                    if (!selectedDepartmentId || option.dataset.department === selectedDepartmentId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Initial filter
            filterJobTitles();
            
            // Filter job titles when department changes
            departmentSelect.addEventListener('change', filterJobTitles);
        });
    </script>
</x-dashboard-layout> 