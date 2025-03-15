<x-dashboard-layout>
    <x-slot name="title">{{ $poll->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    {{ session('warning') }}
                </div>
            @endif
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $poll->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('polls.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Polls</a>
                    <a href="{{ route('polls.results', $poll) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">View Results</a>
                </div>
            </div>
            
            @if($poll->is_mandatory)
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                This is a mandatory poll. You must respond before you can continue using the system.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mb-6">
                @if($poll->description)
                    <p class="text-gray-700 mb-4">{{ $poll->description }}</p>
                @endif
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Created: {{ $poll->created_at->format('M d, Y') }}
                    </span>
                    
                    @if($poll->start_date)
                        <span>
                            <i class="fas fa-play mr-1"></i>
                            Starts: {{ $poll->start_date->format('M d, Y H:i') }}
                        </span>
                    @endif
                    
                    @if($poll->end_date)
                        <span>
                            <i class="fas fa-flag-checkered mr-1"></i>
                            Ends: {{ $poll->end_date->format('M d, Y H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            
            @if(!$poll->isActive())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                This poll is currently not active.
                                @if($poll->start_date && $poll->start_date->gt(now()))
                                    It will become active on {{ $poll->start_date->format('M d, Y H:i') }}.
                                @elseif($poll->end_date && $poll->end_date->lt(now()))
                                    It ended on {{ $poll->end_date->format('M d, Y H:i') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($userHasResponded)
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                You have already responded to this poll. Thank you for your participation!
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('polls.respond', $poll) }}" class="bg-gray-50 p-6 rounded-lg">
                    @csrf
                    <h2 class="text-lg font-medium mb-4">Please select your answer:</h2>
                    
                    <div class="space-y-3">
                        @foreach($poll->options as $option)
                            <div class="flex items-center">
                                <input id="option-{{ $option->id }}" type="radio" name="poll_option_id" value="{{ $option->id }}" required
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="option-{{ $option->id }}" class="ml-3 block text-gray-700">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Submit Response
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-dashboard-layout> 