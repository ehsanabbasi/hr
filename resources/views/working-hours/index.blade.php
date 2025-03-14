<x-dashboard-layout>
    <x-slot name="title">Working Hours</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Working Hours</h1>

            <a href="{{ route('working-hours.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mb-4 inline-block">
                Add Working Hours
            </a>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Start Time
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                End Time
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Break Start Time
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Break End Time
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Total Hours
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-600 font-bold uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workingHours as $workingHour)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->start_time->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->end_time->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->break_start_time ? $workingHour->break_start_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->break_end_time ? $workingHour->break_end_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $workingHour->total_hours }} hours
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    <a href="{{ route('working-hours.edit', $workingHour) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('working-hours.destroy', $workingHour) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this working hour entry?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $workingHours->links() }}
        </div>
    </div>
</x-dashboard-layout> 