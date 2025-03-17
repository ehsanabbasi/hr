<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Working Hours Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Working Hours This Month</h2>
                <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                        @php
                            $percentage = $requiredHoursThisMonth > 0 
                                ? min(100, ($hoursWorkedThisMonth / $requiredHoursThisMonth) * 100) 
                                : 0;
                        @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ round($hoursWorkedThisMonth, 1) }} / {{ $requiredHoursThisMonth }} hours</span>
                </div>
                <div class="mt-2 text-right">
                    <a href="{{ route('working-hours.index') }}" class="text-sm text-blue-600 hover:underline">View Details</a>
                </div>
            </div>
        </div>

        <!-- Birthdays & Anniversaries Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">This Week's Celebrations</h2>
                
                <div class="mb-4">
                    <h3 class="text-lg font-medium mb-2">Birthdays</h3>
                    @if($birthdaysThisWeek->isEmpty())
                        <p class="text-gray-500">No birthdays this week.</p>
                    @else
                        <ul class="space-y-1">
                            @foreach($birthdaysThisWeek as $user)
                                <li class="flex items-center">
                                    <span class="text-blue-600 mr-2">🎂</span>
                                    <span>{{ $user->name }} - {{ $user->birthday->format('M d') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                
                <div>
                    <h3 class="text-lg font-medium mb-2">Work Anniversaries</h3>
                    @if($anniversariesThisWeek->isEmpty())
                        <p class="text-gray-500">No work anniversaries this week.</p>
                    @else
                        <ul class="space-y-1">
                            @foreach($anniversariesThisWeek as $user)
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">🎉</span>
                                    <span>{{ $user->name }} - {{ $user->start_date->diffInYears(now()) }} years</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mandatory Items Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">{{ $isAdmin ? 'Pending Mandatory Items' : 'Your Pending Mandatory Items' }}</h2>
            
            <!-- Mandatory Surveys -->
            @if($mandatorySurveys->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-3">Mandatory Surveys</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    @if($isAdmin)
                                        <th class="py-3 px-6 text-left bg-gray-100">Pending Users</th>
                                    @endif
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mandatorySurveys as $survey)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $survey->title }}</td>
                                        @if($isAdmin)
                                            <td class="py-3 px-6">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($survey->users->take(3) as $pendingUser)
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                            {{ $pendingUser->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($survey->users->count() > 3)
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            +{{ $survey->users->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                        <td class="py-3 px-6">
                                            <a href="{{ route('surveys.show', $survey) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $isAdmin ? 'View Survey' : 'Complete Now' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            <!-- Mandatory Polls -->
            @if(($isAdmin && count($mandatoryPolls) > 0) || (!$isAdmin && $mandatoryPolls->isNotEmpty()))
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-3">Mandatory Polls</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    @if($isAdmin)
                                        <th class="py-3 px-6 text-left bg-gray-100">Pending Users</th>
                                    @endif
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($isAdmin)
                                    @foreach($mandatoryPolls as $pollData)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-6">{{ $pollData['poll']->title }}</td>
                                            <td class="py-3 px-6">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($pollData['users']->take(3) as $pendingUser)
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                            {{ $pendingUser->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($pollData['users']->count() > 3)
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            +{{ $pollData['users']->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-6">
                                                <a href="{{ route('polls.show', $pollData['poll']) }}" class="text-blue-600 hover:text-blue-900">View Poll</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($mandatoryPolls as $poll)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-6">{{ $poll->title }}</td>
                                            <td class="py-3 px-6">
                                                <a href="{{ route('polls.show', $poll) }}" class="text-blue-600 hover:text-blue-900">Complete Now</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            <!-- Mandatory Certificates -->
            @if($mandatoryCertificates->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-3">Mandatory Certificates</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    @if($isAdmin)
                                        <th class="py-3 px-6 text-left bg-gray-100">Pending Users</th>
                                    @endif
                                    <th class="py-3 px-6 text-left bg-gray-100">Due Date</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mandatoryCertificates as $certificate)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $certificate->title }}</td>
                                        @if($isAdmin)
                                            <td class="py-3 px-6">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($certificate->userCertificates->take(3) as $userCert)
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                            {{ $userCert->user->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($certificate->userCertificates->count() > 3)
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            +{{ $certificate->userCertificates->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                        <td class="py-3 px-6">
                                            {{ $certificate->due_date ? $certificate->due_date->format('M d, Y') : 'No due date' }}
                                        </td>
                                        <td class="py-3 px-6">
                                            @if($isAdmin)
                                                <a href="{{ route('certificates.show', $certificate) }}" class="text-blue-600 hover:text-blue-900">View Certificate</a>
                                            @else
                                                @php
                                                    $userCert = $certificate->userCertificates->where('user_id', Auth::id())->first();
                                                @endphp
                                                <a href="{{ route('users.certificates.show', [Auth::user(), $userCert]) }}" class="text-blue-600 hover:text-blue-900">Complete Now</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            <!-- Pending Onboarding Tasks -->
            @if(($isAdmin && count($pendingOnboardingTasks) > 0) || (!$isAdmin && $pendingOnboardingTasks->isNotEmpty()))
                <div>
                    <h3 class="text-lg font-medium mb-3">Pending Onboarding Tasks</h3>
                    
                    @if($isAdmin)
                        @foreach($pendingOnboardingTasks as $userId => $tasks)
                            @php $user = $tasks->first()->user; @endphp
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-2">{{ $user->name }}</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="py-3 px-6 text-left bg-gray-100">Task</th>
                                                <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                                <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tasks as $task)
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="py-3 px-6">{{ $task->title }}</td>
                                                    <td class="py-3 px-6">
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-6">
                                                        <a href="{{ route('users.onboarding', $user) }}" class="text-blue-600 hover:text-blue-900">View Tasks</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6 text-left bg-gray-100">Task</th>
                                        <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                        <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingOnboardingTasks as $task)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-6">{{ $task->title }}</td>
                                            <td class="py-3 px-6">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $task->status === 'ready' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-6">
                                                <form action="{{ route('onboarding.update-status', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $task->status === 'ready' ? 'in_progress' : 'done' }}">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                        {{ $task->status === 'ready' ? 'Start Task' : 'Mark as Done' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
            
            @if(
                $mandatorySurveys->isEmpty() && 
                (($isAdmin && count($mandatoryPolls) === 0) || (!$isAdmin && $mandatoryPolls->isEmpty())) && 
                $mandatoryCertificates->isEmpty() && 
                (($isAdmin && count($pendingOnboardingTasks) === 0) || (!$isAdmin && $pendingOnboardingTasks->isEmpty()))
            )
                <div class="bg-green-50 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ $isAdmin ? 'All users have completed their mandatory items!' : 'You have completed all your mandatory items!' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>