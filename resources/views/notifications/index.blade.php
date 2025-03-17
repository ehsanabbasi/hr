<x-dashboard-layout>
    <x-slot name="title">Notifications</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Notifications</h2>
                
                @if($notifications->where('read_at', null)->count() > 0)
                    <button id="mark-all-read" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Mark All as Read
                    </button>
                @endif
            </div>
            
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <div class="p-4 {{ is_null($notification->read_at) ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-50 transition">
                            <div class="flex items-start">
                                <div class="mr-4 text-{{ is_null($notification->read_at) ? 'blue' : 'gray' }}-500 pt-1">
                                    <i class="{{ $notification->icon_class }} fa-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $notification->title }}</h3>
                                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-gray-600">{{ $notification->content }}</p>
                                    <div class="mt-3 flex space-x-4">
                                        <a href="{{ url('/notifications/' . $notification->id) }}" class="text-sm text-blue-600 hover:text-blue-800 transition">
                                            View details
                                        </a>
                                        @if(is_null($notification->read_at))
                                            <button class="mark-read-btn text-sm text-gray-600 hover:text-gray-800 transition" data-id="{{ $notification->id }}">
                                                Mark as read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-bell text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-600 mb-2">You don't have any notifications yet.</p>
                    <p class="text-gray-500 text-sm">Notifications will appear here when you receive feedback or updates.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark single notification as read
            const markReadButtons = document.querySelectorAll('.mark-read-btn');
            markReadButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const notificationId = this.dataset.id;
                    
                    fetch(`/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notificationElement = this.closest('div.p-4');
                            notificationElement.classList.remove('bg-blue-50');
                            notificationElement.classList.add('bg-white');
                            this.remove();
                        }
                    });
                });
            });
            
            // Mark all as read
            const markAllButton = document.getElementById('mark-all-read');
            if (markAllButton) {
                markAllButton.addEventListener('click', function() {
                    fetch('/notifications/mark-all-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-dashboard-layout> 