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
            
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button id="tab-info" class="tab-button py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Basic Information
                    </button>
                    <button id="tab-documents" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Documents
                    </button>
                    <button id="tab-onboarding" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Onboarding Progress
                    </button>
                </nav>
            </div>
            
            <!-- Tab Content: Basic Information -->
            <div id="content-info" class="tab-content">
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
            
            <!-- Tab Content: Documents -->
            <div id="content-documents" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">User Documents</h2>
                    @can('create', [App\Models\UserDocument::class, $user])
                        <a href="{{ route('users.documents.create', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload Document</a>
                    @endcan
                </div>
                
                @if($user->documents->isEmpty())
                    <div class="bg-gray-100 p-4 rounded-md">
                        <p>No documents found for this user.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Type</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Uploaded By</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->documents()->with(['documentType', 'uploader'])->latest()->take(5)->get() as $document)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">
                                            <a href="{{ route('users.documents.show', [$user, $document]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                                {{ $document->title }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-6">{{ $document->documentType->name }}</td>
                                        <td class="py-3 px-6">{{ $document->uploader->name }}</td>
                                        <td class="py-3 px-6">{{ $document->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3 px-6 flex space-x-2">
                                            <a href="{{ route('users.documents.download', [$user, $document]) }}" class="text-green-600 hover:text-green-900">Download</a>
                                            
                                            @can('delete', $document)
                                                <form action="{{ route('users.documents.destroy', [$user, $document]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('users.documents.index', $user) }}" class="text-blue-600 hover:underline">
                            View all documents
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Tab Content: Onboarding Progress -->
            <div id="content-onboarding" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Onboarding Progress</h2>
                    <a href="{{ route('users.onboarding', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        View All Tasks
                    </a>
                </div>
                
                @php
                    $onboardingTasks = $user->onboardingTasks;
                    $totalTasks = count($onboardingTasks);
                    $completedTasks = $onboardingTasks->where('status', 'done')->count();
                    $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                @endphp
                
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-700">
                        <span>{{ $completedTasks }} of {{ $totalTasks }} tasks completed</span>
                        <span>{{ round($progressPercentage) }}%</span>
                    </div>
                </div>
                
                @if($totalTasks > 0)
                    <div class="mt-4">
                        <ul class="divide-y divide-gray-200 border rounded-lg overflow-hidden">
                            @foreach($onboardingTasks as $task)
                                <li class="p-4 flex items-center justify-between bg-white hover:bg-gray-50">
                                    <div class="flex items-center flex-grow">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center">
                                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</h3>
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                                    {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 
                                                    ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </div>
                                            @if($task->description)
                                                <p class="text-xs text-gray-500 truncate mt-1">{{ Str::limit($task->description, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex items-center text-sm text-gray-500">
                                        @if($task->completed_at)
                                            <span class="mr-3">Completed: {{ $task->completed_at->format('M d, Y') }}</span>
                                        @else
                                            <span class="mr-3">Created: {{ $task->created_at->format('M d, Y') }}</span>
                                        @endif
                                        <a href="{{ route('onboarding.edit', $task) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    @if($totalTasks > 10)
                        <div class="mt-2 text-right">
                            <a href="{{ route('users.onboarding', $user) }}" class="text-blue-600 hover:underline">
                                View all {{ $totalTasks }} tasks
                            </a>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 p-4 rounded-md">
                        <p>No onboarding tasks assigned yet.</p>
                        @can('create', App\Models\OnboardingTask::class)
                            <a href="{{ route('onboarding.create', ['user_id' => $user->id]) }}" class="text-blue-600 hover:underline">
                                Create onboarding tasks
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all tab buttons and content
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            // Add click event to each tab button
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Get the tab id from the button id
                    const tabId = button.id.replace('tab-', '');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show the selected tab content
                    document.getElementById(`content-${tabId}`).classList.remove('hidden');
                    
                    // Update active tab styling
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    button.classList.remove('border-transparent', 'text-gray-500');
                    button.classList.add('border-blue-500', 'text-blue-600');
                });
            });
        });
    </script>
</x-dashboard-layout> 