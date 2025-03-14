<x-dashboard-layout>
    <x-slot name="title">Departments</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Departments</h1>
                <a href="{{ route('departments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Department</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Name</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Head of Department</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $department->name }}</td>
                                <td class="py-3 px-6">{{ $department->head ? $department->head->name : 'Not assigned' }}</td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('departments.show', $department) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('departments.edit', $department) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout> 