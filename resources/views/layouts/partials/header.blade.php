<div class="px-4 py-3 flex justify-between items-center">
    <div class="flex items-center">
        <button id="sidebar-toggle" class="p-1 mr-4 text-gray-600 hover:text-gray-900 focus:outline-none sm:hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Notification Bell -->
        <div x-data="{
            isOpen: false,
            unreadCount: 0,
            notifications: [],
            
            init() {
                this.fetchNotifications();
                // Refresh notifications every minute
                setInterval(() => this.fetchNotifications(), 60000);
            },
            
            fetchNotifications() {
                fetch('{{ route('notifications.latest') }}')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.count;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            },
            
            markAllAsRead() {
                fetch('{{ route('notifications.mark-all-as-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications.forEach(notification => {
                            notification.read_at = new Date();
                        });
                        this.unreadCount = 0;
                    }
                });
            },
            
            getIconClass(notification) {
                if (notification.icon) {
                    return notification.icon;
                }
                
                // Default icons based on type
                const iconMap = {
                    'feedback': 'fas fa-comment-dots',
                    'facility_need': 'fas fa-tools',
                    'onboarding_task': 'fas fa-tasks',
                    'leave_request': 'fas fa-calendar-alt',
                    'system': 'fas fa-bell'
                };
                
                return iconMap[notification.type] || 'fas fa-bell';
            },
            
            formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) {
                    return 'just now';
                }
                
                const diffInMinutes = Math.floor(diffInSeconds / 60);
                if (diffInMinutes < 60) {
                    return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
                }
                
                const diffInHours = Math.floor(diffInMinutes / 60);
                if (diffInHours < 24) {
                    return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                }
                
                const diffInDays = Math.floor(diffInHours / 24);
                if (diffInDays < 7) {
                    return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                }
                
                return date.toLocaleDateString();
            }
        }" class="relative">
            <button @click="isOpen = !isOpen" class="relative p-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                
                <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" 
                      class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                </span>
            </button>
            
            <div x-show="isOpen" @click.away="isOpen = false" 
                 class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95">
                
                <div class="px-4 py-2 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                        <button x-show="unreadCount > 0" @click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800">
                            Mark all as read
                        </button>
                    </div>
                </div>
                
                <div class="max-h-60 overflow-y-auto">
                    <template x-if="notifications.length > 0">
                        <div>
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="`/notifications/${notification.id}`" 
                                   class="block px-4 py-2 hover:bg-gray-100 transition duration-150 ease-in-out"
                                   :class="{ 'bg-blue-50': !notification.read_at }">
                                    <div class="flex">
                                        <div class="mr-3" :class="notification.read_at ? 'text-gray-500' : 'text-blue-500'">
                                            <i :class="getIconClass(notification)"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                            <p class="text-xs text-gray-600 truncate" x-text="notification.content"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                    
                    <template x-if="notifications.length === 0">
                        <div class="px-4 py-6 text-center text-gray-500">
                            <p>No notifications yet</p>
                        </div>
                    </template>
                </div>
                
                <div class="px-4 py-2 border-t border-gray-200">
                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800">
                        View all notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- User Menu -->
        <div x-data="{ isOpen: false }" class="relative">
            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                <span class="sr-only">Open user menu</span>
                @if(auth()->user()->avatar)
                    <img class="w-10 h-10 rounded-full" src="{{ auth()->user()->avatar }}" alt="user photo">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-500 text-white">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
            </button>
            
            <!-- Dropdown menu -->
            <div class="hidden absolute right-0 z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow" id="user-dropdown">
                <div class="py-3 px-4">
                    <span class="block text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                    <span class="block text-sm text-gray-500 truncate">{{ auth()->user()->email }}</span>
                </div>
                <ul class="py-1" aria-labelledby="user-menu-button">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div> 