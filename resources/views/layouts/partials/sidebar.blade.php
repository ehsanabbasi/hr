<div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
    <a href="{{ route('dashboard') }}" class="flex items-center mb-5 pl-2.5">
        <span class="self-center text-xl font-semibold whitespace-nowrap text-white">{{ config('app.name') }}</span>
    </a>
    
    <ul class="space-y-2 font-medium">
        @foreach(config('navigation.sidebar') as $index => $item)
            <li>
                @if(isset($item['submenu']))
                    <button type="button" onclick="toggleSubmenu('dropdown-{{ $index }}')" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-gray-700">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white"></i>
                        @endif
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $item['label'] }}</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="dropdown-{{ $index }}" class="hidden py-2 space-y-2">
                        @foreach($item['submenu'] as $submenu)
                            <li>
                                <a href="{{ route($submenu['route']) }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-gray-700">{{ $submenu['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a href="{{ route($item['route']) }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs($item['route']) ? 'bg-gray-700' : '' }}">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white {{ request()->routeIs($item['route']) ? 'text-white' : '' }}"></i>
                        @endif
                        <span class="ml-3">{{ $item['label'] }}</span>
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