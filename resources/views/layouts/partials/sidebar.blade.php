<div class="h-full flex flex-col bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100 shadow-xl">
    <!-- Logo/Brand Header -->
    <div class="px-6 py-5 border-b border-gray-700/50">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="self-center text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">{{ config('app.name') }}</span>
        </a>
    </div>
    
    <!-- Navigation -->
    <div class="overflow-y-auto flex-grow px-4 py-5 custom-scrollbar">
        <ul class="space-y-1.5 font-medium">
            @foreach(config('navigation.sidebar') as $index => $item)
                @php
                    $hasActiveSubmenu = false;
                    if(isset($item['submenu'])) {
                        foreach($item['submenu'] as $submenu) {
                            if(request()->routeIs($submenu['route'])) {
                                $hasActiveSubmenu = true;
                                break;
                            }
                        }
                    }
                    $isActive = (isset($item['route']) && request()->routeIs($item['route'])) || $hasActiveSubmenu;
                @endphp
                <li class="transition-all duration-200 {{ $isActive ? 'bg-gray-700/40 rounded-lg shadow-sm' : '' }}">
                    @if(isset($item['submenu']))
                        <button type="button" 
                                data-dropdown-id="dropdown-{{ $index }}" 
                                class="flex items-center justify-between w-full p-3 text-sm rounded-lg transition-all duration-200 
                                {{ $isActive ? 'text-white' : 'text-gray-300' }} 
                                {{ $hasActiveSubmenu ? 'bg-gray-700/40' : 'hover:bg-gray-700/40' }}">
                            <div class="flex items-center">
                                @if(isset($item['icon']))
                                    <i class="{{ $item['icon'] }} w-5 h-5 {{ $isActive ? 'text-blue-400' : 'text-gray-400' }} transition-colors"></i>
                                @endif
                                <span class="ml-3 font-medium">{{ $item['label'] }}</span>
                            </div>
                            <svg id="arrow-{{ $index }}" class="w-3 h-3 transition-transform duration-200 {{ $hasActiveSubmenu ? 'rotate-180' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul id="dropdown-{{ $index }}" 
                            class="{{ $hasActiveSubmenu ? '' : 'hidden' }} 
                            transition-all duration-300 py-2 space-y-1 pl-4 border-l border-gray-700/40 ml-6 mt-1">
                            @foreach($item['submenu'] as $submenu)
                                <li>
                                    <a href="{{ route($submenu['route']) }}" 
                                       class="flex items-center w-full p-2 text-sm rounded-lg transition-all duration-200
                                       {{ request()->routeIs($submenu['route']) ? 'text-blue-400 bg-gray-700/40' : 'text-gray-300 hover:bg-gray-700/40 hover:text-white' }}">
                                        @if(request()->routeIs($submenu['route']))
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-3"></div>
                                        @endif
                                        <span class="{{ request()->routeIs($submenu['route']) ? 'ml-0' : 'ml-4.5' }}">
                                            {{ $submenu['label'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center p-3 text-sm rounded-lg transition-all duration-200
                           {{ $isActive ? 'bg-gray-700/40 text-white' : 'text-gray-300 hover:bg-gray-700/40 hover:text-white' }}">
                            @if(isset($item['icon']))
                                <i class="{{ $item['icon'] }} w-5 h-5 {{ $isActive ? 'text-blue-400' : 'text-gray-400' }} transition-colors"></i>
                            @endif
                            <span class="ml-3 font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    
    <!-- Footer -->
    <div class="mt-auto border-t border-gray-700/50 p-4">
        <div class="flex items-center justify-center">
            <span class="text-xs text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}</span>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar styling */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(75, 85, 99, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(107, 114, 128, 0.5);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(107, 114, 128, 0.7);
    }
    
    /* Hide scrollbar for IE, Edge and Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(107, 114, 128, 0.5) rgba(75, 85, 99, 0.1);
    }
</style>

<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to each dropdown button
        document.querySelectorAll('button[data-dropdown-id]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = this.getAttribute('data-dropdown-id');
                toggleSubmenu(id);
            });
        });

        // Close submenus when clicking outside
        document.addEventListener('click', function(e) {
            // If the click is not on a menu item, close all open menus
            if (!e.target.closest('button[data-dropdown-id]') && !e.target.closest('ul[id^="dropdown-"]')) {
                closeAllSubmenus();
            }
        });
    });

    // Close all open submenus
    function closeAllSubmenus() {
        document.querySelectorAll('ul[id^="dropdown-"]').forEach(submenu => {
            if (!submenu.classList.contains('hidden')) {
                const index = submenu.id.split('-')[1];
                const arrow = document.getElementById('arrow-' + index);
                
                if (arrow) {
                    arrow.classList.remove('rotate-180');
                }
                
                // Animate closing
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                submenu.offsetHeight; // Force reflow
                submenu.style.maxHeight = '0px';
                
                // Set display to none after animation
                setTimeout(() => {
                    submenu.classList.add('hidden');
                    submenu.style.display = 'none';
                    submenu.style.maxHeight = null;
                }, 200);
            }
        });
    }

    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const index = id.split('-')[1];
        const arrow = document.getElementById('arrow-' + index);
        
        if (!submenu) return;
        
        const isHidden = submenu.classList.contains('hidden');
        
        // Update arrow rotation
        if (isHidden) {
            arrow.classList.add('rotate-180');
        } else {
            arrow.classList.remove('rotate-180');
        }
        
        if (isHidden) {
            // Close all other submenus
            document.querySelectorAll('ul[id^="dropdown-"]').forEach(el => {
                if (el.id !== id && !el.classList.contains('hidden')) {
                    const otherIndex = el.id.split('-')[1];
                    const otherArrow = document.getElementById('arrow-' + otherIndex);
                    
                    if (otherArrow) {
                        otherArrow.classList.remove('rotate-180');
                    }
                    
                    // Animate closing
                    el.style.maxHeight = el.scrollHeight + 'px';
                    el.offsetHeight; // Force reflow
                    el.style.maxHeight = '0px';
                    
                    // Set display to none after animation
                    setTimeout(() => {
                        el.classList.add('hidden');
                        el.style.display = 'none';
                        el.style.maxHeight = null;
                    }, 200);
                }
            });
            
            // Open this submenu with animation
            submenu.classList.remove('hidden');
            submenu.style.maxHeight = '0px';
            submenu.style.display = 'block';
            
            // Trigger reflow for animation
            submenu.offsetHeight;
            
            // Set maxHeight for animation
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            
            // After animation completes, remove maxHeight constraint
            setTimeout(() => {
                submenu.style.maxHeight = 'none';
            }, 300);
        } else {
            // Animate closing
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            submenu.offsetHeight; // Force reflow
            submenu.style.maxHeight = '0px';
            
            // Set display to none after animation
            setTimeout(() => {
                submenu.classList.add('hidden');
                submenu.style.display = 'none';
                submenu.style.maxHeight = null;
            }, 200);
        }
    }
</script>