<x-guest-layout>
    <form method="POST" action="{{ route('invitation.register.store') }}" class="p-6 bg-white rounded-lg shadow-md">
        @csrf

        <!-- Invitation Token -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">{{ __('Name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address (Readonly) -->
        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required readonly autocomplete="username"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-0">
            <p class="text-sm text-gray-500 mt-1">
                {{ __('Email address from your invitation. Cannot be changed.') }}
            </p>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                {{ __('Complete Registration') }}
            </button>
        </div>
    </form>

    <!-- Social Login Divider -->
    <div class="flex items-center justify-center my-6">
        <span class="border-t w-full border-gray-300"></span>
        <span class="px-4 text-gray-500 text-sm">{{ __('Or continue with') }}</span>
        <span class="border-t w-full border-gray-300"></span>
    </div>

    <!-- Google Login Button -->
    <div class="px-6">
        <a href="{{ route('auth.google') }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48">
                <defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"/><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"/><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"/><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"/></svg>
            {{ __('Sign up with Google') }}
        </a>
    </div>
</x-guest-layout> 