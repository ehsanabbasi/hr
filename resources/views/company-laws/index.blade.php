<x-dashboard-layout>
    <x-slot name="title">Company Law Settings</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Company Law Settings</h1>

            <form method="POST" action="{{ route('company-laws.update', $companyLaw) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="max_daily_hours" class="block mb-2 text-sm font-medium text-gray-700">Max Daily Hours</label>
                    <input id="max_daily_hours" type="number" name="max_daily_hours" value="{{ old('max_daily_hours', $companyLaw->max_daily_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('max_daily_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="max_daily_break_hours" class="block mb-2 text-sm font-medium text-gray-700">Max Daily Break Hours</label>
                    <input id="max_daily_break_hours" type="number" name="max_daily_break_hours" value="{{ old('max_daily_break_hours', $companyLaw->max_daily_break_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('max_daily_break_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="min_weekly_hours" class="block mb-2 text-sm font-medium text-gray-700">Min Weekly Hours</label>
                    <input id="min_weekly_hours" type="number" name="min_weekly_hours" value="{{ old('min_weekly_hours', $companyLaw->min_weekly_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('min_weekly_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="max_weekly_hours" class="block mb-2 text-sm font-medium text-gray-700">Max Weekly Hours</label>
                    <input id="max_weekly_hours" type="number" name="max_weekly_hours" value="{{ old('max_weekly_hours', $companyLaw->max_weekly_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('max_weekly_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="min_monthly_hours" class="block mb-2 text-sm font-medium text-gray-700">Min Monthly Hours</label>
                    <input id="min_monthly_hours" type="number" name="min_monthly_hours" value="{{ old('min_monthly_hours', $companyLaw->min_monthly_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('min_monthly_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="max_monthly_hours" class="block mb-2 text-sm font-medium text-gray-700">Max Monthly Hours</label>
                    <input id="max_monthly_hours" type="number" name="max_monthly_hours" value="{{ old('max_monthly_hours', $companyLaw->max_monthly_hours) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('max_monthly_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 