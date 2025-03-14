<x-dashboard-layout>
    <x-slot name="title">Feedbacks</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Feedbacks</h1>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Received Feedbacks -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Received Feedbacks</h2>
                
                @if($receivedFeedbacks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">From</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Session</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receivedFeedbacks as $feedback)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $feedback->sender->name }}</td>
                                        <td class="py-3 px-6">{{ $feedback->title }}</td>
                                        <td class="py-3 px-6">{{ $feedback->session ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $feedback->created_at->format('M d, Y') }}</td>
                                        <td class="py-3 px-6">
                                            @if($feedback->read_at)
                                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Read</span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Unread</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">
                                            <a href="{{ route('feedbacks.show', $feedback) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $receivedFeedbacks->links() }}
                    </div>
                @else
                    <p class="text-gray-500">You haven't received any feedback yet.</p>
                @endif
            </div>
            
            <!-- Sent Feedbacks -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Sent Feedbacks</h2>
                
                @if($sentFeedbacks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 text-left bg-gray-100">To</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Session</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                                    <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sentFeedbacks as $feedback)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6">{{ $feedback->receiver->name }}</td>
                                        <td class="py-3 px-6">{{ $feedback->title }}</td>
                                        <td class="py-3 px-6">{{ $feedback->session ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $feedback->created_at->format('M d, Y') }}</td>
                                        <td class="py-3 px-6">
                                            @if($feedback->read_at)
                                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Read</span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Unread</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 flex space-x-2">
                                            <a href="{{ route('feedbacks.show', $feedback) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <form action="{{ route('feedbacks.destroy', $feedback) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sentFeedbacks->links() }}
                    </div>
                @else
                    <p class="text-gray-500">You haven't sent any feedback yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout> 