<x-dashboard-layout>
    <x-slot name="title">Department Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $department->name }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('departments.edit', $department) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                    <a href="{{ route('departments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back</a>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Description</h2>
                <p class="text-gray-700">{{ $department->description ?? 'No description available.' }}</p>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Head of Department</h2>
                @if($department->head)
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3">
                            {{ substr($department->head->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $department->head->name }}</p>
                            <p class="text-sm text-gray-500">{{ $department->head->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-700">No head of department assigned.</p>
                @endif
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Job Titles</h2>
                @if($department->jobTitles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->jobTitles as $jobTitle)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">{{ $jobTitle->name }}</td>
                                        <td class="py-3 px-6">{{ $jobTitle->description ?? 'No description' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-700">No job titles defined for this department.</p>
                @endif
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Department Members</h2>
                @if($department->users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Name</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Email</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Job Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->users as $user)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6">{{ $user->jobTitle ? $user->jobTitle->name : 'Not assigned' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-700">No users in this department.</p>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout> 