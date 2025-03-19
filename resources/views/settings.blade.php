<x-dashboard-layout>
    <x-slot name="title">{{ __('messages.settings') }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-4">{{ __('messages.settings') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('messages.app_settings') }}</p>
            
            <!-- Language Settings Content -->
            <div id="language-content">
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        {{ __('messages.language_settings') }}
                    </h2>
                    
                    @if(session('status'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif
                    
                    <form action="{{ route('language.switch') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('messages.select_language') }}
                            </label>
                            <div class="relative">
                                <select id="locale" name="locale" class="appearance-none bg-white border border-gray-300 rounded-lg py-3 px-4 pr-8 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                                        🇬🇧 English
                                    </option>
                                    <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>
                                        🇫🇷 Français
                                    </option>
                                    <option value="de" {{ app()->getLocale() == 'de' ? 'selected' : '' }}>
                                        🇩🇪 Deutsch
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                                           
                        <div class="flex items-center">
                            <button type="submit" class="inline-flex justify-center items-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Additional Settings Section (Optional) -->
            <div class="mt-8 text-center">
                <p class="text-gray-500 italic text-sm">{{ __('messages.more_settings_coming_soon') }}</p>
            </div>
        </div>
    </div>
</x-dashboard-layout> 