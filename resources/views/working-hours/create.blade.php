<x-dashboard-layout>
    <x-slot name="title">Add Working Hours</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Add Working Hours</h1>

            <form method="POST" action="{{ route('working-hours.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="date" class="block mb-2 text-sm font-medium text-gray-700">Date</label>
                    <input id="date" type="date" name="date" value="{{ old('date') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="start_time" class="block mb-2 text-sm font-medium text-gray-700">Start Time</label>
                    <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="end_time" class="block mb-2 text-sm font-medium text-gray-700">End Time</label>
                    <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="break_start_time" class="block mb-2 text-sm font-medium text-gray-700">Break Start Time</label>
                    <input id="break_start_time" type="time" name="break_start_time" value="{{ old('break_start_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('break_start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="break_end_time" class="block mb-2 text-sm font-medium text-gray-700">Break End Time</label>
                    <input id="break_end_time" type="time" name="break_end_time" value="{{ old('break_end_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('break_end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('working-hours.index') }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 