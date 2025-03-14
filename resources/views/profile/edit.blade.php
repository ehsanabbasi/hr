<x-dashboard-layout>
    <x-slot name="title">Profile</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-4">Profile</h1>
            <p>Update your profile information.</p>
            
            <!-- Profile edit form goes here -->
            <form method="POST" action="{{ route('profile.update') }}" class="mt-6">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="birthday" class="block mb-2 text-sm font-medium text-gray-700">Birthday</label>
                    <input id="birthday" type="date" name="birthday" value="{{ old('birthday', auth()->user()->birthday ? auth()->user()->birthday->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date</label>
                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date', auth()->user()->start_date ? auth()->user()->start_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="emergency_contact_name" class="block mb-2 text-sm font-medium text-gray-700">Emergency Contact Name</label>
                    <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', auth()->user()->emergency_contact_name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="emergency_contact_phone" class="block mb-2 text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                    <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', auth()->user()->emergency_contact_phone) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
