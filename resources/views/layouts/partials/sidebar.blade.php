<div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
    <a href="{{ route('dashboard') }}" class="flex items-center mb-5 pl-2.5">
        <span class="self-center text-xl font-semibold whitespace-nowrap text-white">{{ config('app.name') }}</span>
    </a>
    
    <ul class="space-y-2 font-medium">
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
            <li>
                @if(isset($item['submenu']))
                    <button type="button" onclick="toggleSubmenu('dropdown-{{ $index }}')" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-gray-700 {{ $hasActiveSubmenu ? 'bg-gray-700' : '' }}">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white {{ $hasActiveSubmenu ? 'text-white' : '' }}"></i>
                        @endif
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $item['label'] }}</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-{{ $index }}" class="{{ $hasActiveSubmenu ? '' : 'hidden' }} py-2 space-y-2">
                        @foreach($item['submenu'] as $submenu)
                            <li>
                                <a href="{{ route($submenu['route']) }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 {{ request()->routeIs($submenu['route']) ? 'bg-gray-700' : '' }}">
                                    @if(request()->routeIs($submenu['route']))
                                        <svg class="w-4 h-4 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    {{ $submenu['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a href="{{ route($item['route']) }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs($item['route']) ? 'bg-gray-700' : '' }}">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white {{ request()->routeIs($item['route']) ? 'text-white' : '' }}"></i>
                        @endif
                        <span class="ml-3">{{ $item['label'] }}</span>
                        @if(request()->routeIs($item['route']))
                            <svg class="w-4 h-4 ml-auto text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>

<script>
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        if (submenu) {
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.style.display = 'block';
                console.log('Submenu opened:', id);
            } else {
                submenu.classList.add('hidden');
                submenu.style.display = 'none';
                console.log('Submenu closed:', id);
            }
        }
    }
</script>