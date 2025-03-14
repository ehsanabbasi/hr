<div class="px-4 py-3 flex justify-between items-center">
    <!-- Mobile menu button -->
    <button id="sidebar-toggle" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>
    
    <div class="flex items-center">
        <h2 class="text-xl font-semibold">{{ $title ?? 'Dashboard' }}</h2>
    </div>

    <!-- User dropdown -->
    <div class="relative">
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