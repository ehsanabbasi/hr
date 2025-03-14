<x-dashboard-layout>
    <x-slot name="title">Feedback Details</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Feedback Details</h1>
                <a href="{{ route('feedbacks.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="mb-4">
                    <h2 class="text-xl font-semibold">{{ $feedback->title }}</h2>
                    <div class="text-sm text-gray-500 mt-1">
                        {{ $feedback->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">From</h3>
                        <p class="mt-1">{{ $feedback->sender->name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">To</h3>
                        <p class="mt-1">{{ $feedback->receiver->name }}</p>
                    </div>
                    
                    @if($feedback->session)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Session</h3>
                            <p class="mt-1">{{ $feedback->session }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            @if($feedback->read_at)
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                    Read on {{ $feedback->read_at->format('M d, Y h:i A') }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                    Not read yet
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Feedback Content</h3>
                    <div class="bg-white p-4 rounded border border-gray-200">
                        <p class="whitespace-pre-line">{{ $feedback->content }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                @if(Auth::id() == $feedback->receiver_id)
                    <a href="{{ route('feedbacks.create', $feedback->sender) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Reply
                    </a>
                @else
                    <div></div>
                @endif
                
                @if(Auth::id() == $feedback->sender_id)
                    <form action="{{ route('feedbacks.destroy', $feedback) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" 
                                onclick="return confirm('Are you sure you want to delete this feedback?')">
                            Delete Feedback
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout> 