<x-dashboard-layout>
    <x-slot name="title">Career Opportunities</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Career Opportunities</h1>
                @can('create', App\Models\CareerOpportunity::class)
                    <a href="{{ route('career-opportunities.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Opportunity</a>
                @endcan
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-4">
                <form action="{{ route('career-opportunities.index') }}" method="GET" class="flex items-center space-x-4">
                    <div class="flex-1">
                        <select name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Filter
                    </button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left bg-gray-100">Title</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Department</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Level</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Status</th>
                            <th class="py-3 px-6 text-center bg-gray-100">Candidates</th>
                            <th class="py-3 px-6 text-left bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($careerOpportunities as $opportunity)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $opportunity->title }}</td>
                                <td class="py-3 px-6">{{ $opportunity->department->name }}</td>
                                <td class="py-3 px-6">{{ ucfirst($opportunity->level) }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $opportunity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $opportunity->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    @can('viewCandidates', $opportunity)
                                        <a href="{{ route('career-opportunities.candidates.index', $opportunity) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                            <span class="mr-1">{{ $opportunity->candidates->count() }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-600">{{ $opportunity->candidates->count() }}</span>
                                    @endcan
                                </td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('career-opportunities.show', $opportunity) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    
                                    @can('update', $opportunity)
                                        <a href="{{ route('career-opportunities.edit', $opportunity) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endcan
                                    
                                    @can('delete', $opportunity)
                                        <form action="{{ route('career-opportunities.destroy', $opportunity) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this opportunity?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-3 px-6 text-center">No career opportunities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $careerOpportunities->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout> 