<x-dashboard-layout>
    <x-slot name="title">Edit Candidate: {{ $candidate->name }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Edit Candidate: {{ $candidate->name }}</h1>
            
            <form method="POST" action="{{ route('career-opportunities.candidates.update', [$careerOpportunity, $candidate]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $candidate->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $candidate->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $candidate->phone) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="gender" class="block mb-2 text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $candidate->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $candidate->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $candidate->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="birthday" class="block mb-2 text-sm font-medium text-gray-700">Birthday</label>
                        <input id="birthday" type="date" name="birthday" value="{{ old('birthday', $candidate->birthday ? $candidate->birthday->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('birthday')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="not-checked" {{ old('status', $candidate->status) === 'not-checked' ? 'selected' : '' }}>Not Checked</option>
                        <option value="accepted" {{ old('status', $candidate->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ old('status', $candidate->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="resume" class="block mb-2 text-sm font-medium text-gray-700">Resume (PDF only)</label>
                    @if($candidate->resume_path)
                        <div class="mb-2 flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Current resume:</span>
                            <a href="{{ route('career-opportunities.candidates.resume', [$careerOpportunity, $candidate]) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                Download
                            </a>
                        </div>
                    @endif
                    <input id="resume" type="file" name="resume" accept=".pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Maximum file size: 10MB. Leave empty to keep current resume.</p>
                    @error('resume')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $candidate->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <a href="{{ route('career-opportunities.candidates.show', [$careerOpportunity, $candidate]) }}" class="text-gray-600 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Candidate
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout> 