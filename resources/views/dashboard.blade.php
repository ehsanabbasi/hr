<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Birthdays this week -->
        <div class="bg-blue-100 p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-2 text-blue-800">Birthdays this Week</h2>
            @if(count($birthdaysThisWeek) > 0)
                <ul class="list-disc pl-5">
                    @foreach($birthdaysThisWeek as $user)
                        <li class="text-blue-700">{{ $user->name }} - {{ $user->birthday->format('M d') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-blue-700">No birthdays this week.</p>
            @endif
        </div>

        <!-- Work anniversaries this week -->
        <div class="bg-green-100 p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-2 text-green-800">Work Anniversaries this Week</h2>
            @if(count($anniversariesThisWeek) > 0)
                <ul class="list-disc pl-5">
                    @foreach($anniversariesThisWeek as $user)
                        <li class="text-green-700">{{ $user->name }} - {{ $user->start_date->format('M d') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-green-700">No work anniversaries this week.</p>
            @endif
        </div>

        <!-- Hours worked vs. required hours -->
        <div class="bg-yellow-100 p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-bold mb-2 text-yellow-800">Hours Worked this Month</h2>
            <p class="text-yellow-700">Hours worked: {{ $hoursWorkedThisMonth }} hours</p>
            <p class="text-yellow-700">Required hours: {{ $requiredHoursThisMonth }} hours</p>
        </div>
    </div>
</x-dashboard-layout>