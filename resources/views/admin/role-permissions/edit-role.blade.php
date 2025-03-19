<x-dashboard-layout>
    <x-slot name="title">{{ __('Edit Role') }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-4">{{ __('Edit Role') }}: {{ ucfirst($role->name) }}</h1>
            
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <form action="{{ route('admin.role-permissions.update-role', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                        <input type="text" class="form-control appearance-none bg-white border border-gray-300 rounded-lg py-3 px-4 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 max-h-96 overflow-y-auto bg-white p-4 rounded-lg border border-gray-200">
                            @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                <div>
                                    @foreach($chunk as $permission)
                                        <div class="flex items-start mb-2">
                                            <div class="flex items-center h-5">
                                                <input id="permission{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="permission{{ $permission->id }}" class="font-medium text-gray-700">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.role-permissions.roles') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout> 