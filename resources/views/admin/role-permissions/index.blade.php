<x-dashboard-layout>
    <x-slot name="title">{{ __('Role & Permission Management') }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ __('User Role & Permission Assignments') }}</h1>
                <div class="space-x-2">
                    <a href="{{ route('admin.role-permissions.roles') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Manage Roles
                    </a>
                    <a href="{{ route('admin.role-permissions.permissions') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Manage Permissions
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-gray-200 mb-6 shadow-sm">
                <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Search & Filter') }}
                </h3>
                
                <form action="{{ route('admin.role-permissions.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Search Field -->
                        <div class="md:col-span-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" 
                                       class="block w-full h-10 pl-10 pr-20 py-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                       placeholder="Search by name or email" 
                                       value="{{ $search ?? '' }}">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-1.5">
                                    <button type="submit" 
                                            class="inline-flex items-center h-8 px-3 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role Filter -->
                        <div class="md:col-span-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Filter by Role</label>
                            <div class="relative">
                                <select id="role" name="role" 
                                        class="block w-full h-10 pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm appearance-none" 
                                        onchange="this.form.submit()">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ isset($roleFilter) && $roleFilter == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reset Button -->
                        <div class="md:col-span-2 flex items-end">
                            <a href="{{ route('admin.role-permissions.index') }}" 
                               class="inline-flex justify-center items-center w-full h-10 px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters - Toggle Section -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button type="button" id="toggleAdvancedFilters" class="flex items-center text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Advanced Filters
                        </button>
                        
                        <div id="advancedFilters" class="hidden mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="permission" class="block text-sm font-medium text-gray-700 mb-2">Has Permission</label>
                                <select id="permission" name="permission" 
                                        class="block w-full h-10 pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm appearance-none">
                                    <option value="">Any Permission</option>
                                    @foreach($permissions ?? [] as $permission)
                                        <option value="{{ $permission->id }}" {{ isset($permissionFilter) && $permissionFilter == $permission->id ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Add more filters as needed -->
                            
                            <!-- Apply Advanced Filters Button -->
                            <div class="md:col-span-3 flex justify-end mt-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direct Permissions</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($user->permissions->count() > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $user->permissions->count() }} permissions
                                            </span>
                                        @else
                                            @foreach($user->permissions as $permission)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-1">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.role-permissions.edit', $user) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Edit Roles/Permissions
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleAdvancedFilters');
        const advancedFilters = document.getElementById('advancedFilters');
        
        if(toggleButton && advancedFilters) {
            toggleButton.addEventListener('click', function() {
                advancedFilters.classList.toggle('hidden');
                // Change the plus icon to minus when expanded
                const svg = toggleButton.querySelector('svg');
                if(advancedFilters.classList.contains('hidden')) {
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                } else {
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />';
                }
            });
        }
    });
</script> 