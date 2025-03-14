<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h1>
            <p>You're logged in to the dashboard.</p>
            
            <!-- Dashboard content goes here -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Quick Stats</h3>
                    <p>Your dashboard statistics and summary information.</p>
                </div>
                
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Recent Activity</h3>
                    <p>Your recent actions and notifications.</p>
                </div>
                
                <div class="bg-purple-100 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Tasks</h3>
                    <p>Your upcoming tasks and deadlines.</p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
