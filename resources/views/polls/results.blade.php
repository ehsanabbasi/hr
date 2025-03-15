<x-dashboard-layout>
    <x-slot name="title">Poll Results: {{ $poll->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Poll Results: {{ $poll->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('polls.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Polls</a>
                    <a href="{{ route('polls.show', $poll) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">View Poll</a>
                </div>
            </div>
            
            <div class="mb-6">
                @if($poll->description)
                    <p class="text-gray-700 mb-4">{{ $poll->description }}</p>
                @endif
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-users mr-1"></i>
                        Total Responses: {{ $poll->responses->count() }}
                    </span>
                    
                    <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Created: {{ $poll->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h2 class="text-lg font-medium mb-4">Results:</h2>
                
                @if($poll->responses->count() > 0)
                    <div class="space-y-6">
                        @foreach($poll->options as $option)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">{{ $option->option_text }}</span>
                                    <span class="text-gray-700">
                                        {{ $option->responses->count() }} votes ({{ $option->getResponsePercentage() }}%)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $option->getResponsePercentage() }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No responses yet.</p>
                @endif
            </div>
            
            @if($poll->responses->count() > 0)
                <div class="mt-8">
                    <h2 class="text-lg font-medium mb-4">Response Details:</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">User</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Response</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($poll->responses as $response)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $response->user->name }}</td>
                                        <td class="py-3 px-6">{{ $response->option->option_text }}</td>
                                        <td class="py-3 px-6">{{ $response->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout> 