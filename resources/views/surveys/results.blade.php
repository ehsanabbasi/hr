<x-dashboard-layout>
    <x-slot name="title">Survey Results: {{ $survey->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Survey Results: {{ $survey->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('surveys.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Back to Surveys</a>
                    <a href="{{ route('surveys.show', $survey) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">View Survey</a>
                </div>
            </div>
            
            <div class="mb-6">
                @if($survey->description)
                    <p class="text-gray-700 mb-4">{{ $survey->description }}</p>
                @endif
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-users mr-1"></i>
                        Total Participants: {{ $survey->users()->wherePivot('completed_at', '!=', null)->count() }}
                    </span>
                    
                    <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Created: {{ $survey->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-8">
                @foreach($survey->questions as $question)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-medium mb-4">Question {{ $loop->iteration }}: {{ $question->question_text }}</h2>
                        
                        @if(count($responsesByQuestion[$question->id]) > 0)
                            @switch($question->question_type)
                                @case('single_choice')
                                @case('multiple_choice')
                                @case('dropdown')
                                    @php
                                        $optionCounts = [];
                                        $totalResponses = count($responsesByQuestion[$question->id]);
                                        
                                        foreach($responsesByQuestion[$question->id] as $response) {
                                            $values = is_array(json_decode($response->response_value, true)) 
                                                ? json_decode($response->response_value, true) 
                                                : [$response->response_value];
                                                
                                            foreach($values as $value) {
                                                if (!isset($optionCounts[$value])) {
                                                    $optionCounts[$value] = 0;
                                                }
                                                $optionCounts[$value]++;
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="space-y-4">
                                        @foreach($optionCounts as $option => $count)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-gray-700">{{ $option }}</span>
                                                    <span class="text-gray-700">
                                                        {{ $count }} responses ({{ round(($count / $totalResponses) * 100) }}%)
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ round(($count / $totalResponses) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break
                                    
                                @case('rating')
                                    @php
                                        $ratings = [];
                                        $totalRating = 0;
                                        $responseCount = count($responsesByQuestion[$question->id]);
                                        
                                        foreach($responsesByQuestion[$question->id] as $response) {
                                            $rating = (int) $response->response_value;
                                            $totalRating += $rating;
                                            
                                            if (!isset($ratings[$rating])) {
                                                $ratings[$rating] = 0;
                                            }
                                            $ratings[$rating]++;
                                        }
                                        
                                        $averageRating = $responseCount > 0 ? round($totalRating / $responseCount, 1) : 0;
                                        ksort($ratings);
                                    @endphp
                                    
                                    <div class="mb-4">
                                        <p class="text-lg font-medium">Average Rating: {{ $averageRating }}</p>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        @foreach($ratings as $rating => $count)
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-gray-700">{{ $rating }} stars</span>
                                                    <span class="text-gray-700">
                                                        {{ $count }} responses ({{ round(($count / $responseCount) * 100) }}%)
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ round(($count / $responseCount) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break
                                    
                                @case('open_ended')
                                @case('date_time')
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
                                                @foreach($responsesByQuestion[$question->id] as $response)
                                                    <tr class="border-b hover:bg-gray-50">
                                                        <td class="py-3 px-6">{{ $response->user->name }}</td>
                                                        <td class="py-3 px-6">{{ $response->response_value }}</td>
                                                        <td class="py-3 px-6">{{ $response->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @break
                            @endswitch
                        @else
                            <p class="text-gray-500">No responses yet.</p>
                        @endif
                    </div>
                @endforeach
            </div>
            
            @if(Auth::user()->hasRole(['admin', 'hr']))
                <div class="mt-8">
                    <h2 class="text-lg font-medium mb-4">Response Details:</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">User</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Completion Date</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($survey->users()->wherePivot('completed_at', '!=', null)->get() as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->pivot->completed_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3 px-6">
                                            <a href="#" class="text-blue-600 hover:text-blue-900" 
                                               onclick="toggleUserResponses({{ $user->id }})">View Responses</a>
                                        </td>
                                    </tr>
                                    <tr id="user-responses-{{ $user->id }}" class="hidden border-b bg-gray-50">
                                        <td colspan="3" class="py-3 px-6">
                                            <div class="space-y-4">
                                                @foreach($survey->questions as $question)
                                                    <div>
                                                        <p class="font-medium">{{ $question->question_text }}</p>
                                                        @php
                                                            $userResponse = $question->getUserResponse($user);
                                                        @endphp
                                                        
                                                        @if($userResponse)
                                                            <p class="ml-4 mt-1">
                                                                @if($question->question_type === 'multiple_choice')
                                                                    @foreach(json_decode($userResponse->response_value, true) as $value)
                                                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 mb-1">{{ $value }}</span>
                                                                    @endforeach
                                                                @else
                                                                    {{ $userResponse->response_value }}
                                                                @endif
                                                            </p>
                                                        @else
                                                            <p class="ml-4 mt-1 text-gray-500">No response</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        function toggleUserResponses(userId) {
            const responsesRow = document.getElementById(`user-responses-${userId}`);
            if (responsesRow.classList.contains('hidden')) {
                responsesRow.classList.remove('hidden');
            } else {
                responsesRow.classList.add('hidden');
            }
        }
    </script>
</x-dashboard-layout>