<x-dashboard-layout>
    <x-slot name="title">User Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back</a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Basic Information</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Email</span>
                            <span class="block mt-1">{{ $user->email }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Department</span>
                            <span class="block mt-1">{{ $user->department ? $user->department->name : 'Not assigned' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Job Title</span>
                            <span class="block mt-1">{{ $user->jobTitle ? $user->jobTitle->name : 'Not assigned' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Birthday</span>
                            <span class="block mt-1">{{ $user->birthday ? $user->birthday->format('M d, Y') : 'Not provided' }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Start Date</span>
                            <span class="block mt-1">{{ $user->start_date ? $user->start_date->format('M d, Y') : 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-2">Emergency Contact</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-3">
                            <span class="block text-sm font-medium text-gray-500">Name</span>
                            <span class="block mt-1">{{ $user->emergency_contact_name ?: 'Not provided' }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Phone</span>
                            <span class="block mt-1">{{ $user->emergency_contact_phone ?: 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <h2 class="text-lg font-semibold mb-2 mt-6">Department Leadership</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @if($user->headOfDepartments->count() > 0)
                            <p class="mb-2">This user is the head of the following departments:</p>
                            <ul class="list-disc pl-5">
                                @foreach($user->headOfDepartments as $department)
                                    <li>{{ $department->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>This user is not the head of any department.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('feedbacks.create', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Send Feedback
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout> 