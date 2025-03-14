<x-dashboard-layout>
    <x-slot name="title">Monthly Working Hours</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Monthly Working Hours - {{ now()->format('F Y') }}</h1>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Date</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workingHours as $workingHour)
                            <tr>
                                <td class="py-3 px-6 border-b">{{ $workingHour->date->format('M d, Y (D)') }}</td>
                                <td class="py-3 px-6 border-b">{{ $workingHour->total_hours }} hours</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <h2 class="text-lg font-bold">Total Hours for the Month: {{ $totalHours }} hours</h2>
            </div>
        </div>
    </div>
</x-dashboard-layout> 