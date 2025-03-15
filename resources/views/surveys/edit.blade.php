<x-dashboard-layout>
    <x-slot name="title">Edit Survey</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Edit Survey</h1>
            
            <form method="POST" action="{{ route('surveys.update', $survey) }}" id="survey-form">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $survey->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $survey->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4 flex space-x-4">
                    <div class="flex items-center">
                        <input id="is_mandatory" type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', $survey->is_mandatory) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_mandatory" class="ml-2 block text-sm text-gray-700">Mandatory</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', $survey->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>
                
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date (Optional)</label>
                        <input id="start_date" type="datetime-local" name="start_date" 
                               value="{{ old('start_date', $survey->start_date ? $survey->start_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">End Date (Optional)</label>
                        <input id="end_date" type="datetime-local" name="end_date" 
                               value="{{ old('end_date', $survey->end_date ? $survey->end_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Assign to Users</label>
                    <div class="border border-gray-300 rounded-md p-4 max-h-60 overflow-y-auto">
                        <div class="mb-2">
                            <div class="flex items-center">
                                <input id="assign-all" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="assign-all" class="ml-2 block text-sm font-medium text-gray-700">Select All Users</label>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($users as $user)
                                <div class="flex items-center">
                                    <input id="user-{{ $user->id }}" type="checkbox" name="users[]" value="{{ $user->id }}"
                                           class="user-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array($user->id, old('users', $survey->users->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label for="user-{{ $user->id }}" class="ml-2 block text-sm text-gray-700">{{ $user->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('users')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Questions</label>
                        <button type="button" id="add-question" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Add Question
                        </button>
                    </div>
                    
                    <div id="questions-container" class="space-y-4">
                        @foreach($survey->questions as $index => $question)
                            <div class="question-item border border-gray-300 rounded-md p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-medium">Question <span class="question-number">{{ $index + 1 }}</span></h3>
                                    <button type="button" class="remove-question text-red-600 hover:text-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                                
                                <div class="mb-3">
                                    <label class="block mb-1 text-sm text-gray-700">Question Text</label>
                                    <input type="text" name="questions[{{ $index }}][question_text]" value="{{ $question->question_text }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="mb-3 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-1 text-sm text-gray-700">Question Type</label>
                                        <select name="questions[{{ $index }}][question_type]" class="question-type w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="single_choice" {{ $question->question_type === 'single_choice' ? 'selected' : '' }}>Single Choice</option>
                                            <option value="multiple_choice" {{ $question->question_type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                            <option value="open_ended" {{ $question->question_type === 'open_ended' ? 'selected' : '' }}>Open Ended</option>
                                            <option value="rating" {{ $question->question_type === 'rating' ? 'selected' : '' }}>Rating Scale</option>
                                            <option value="dropdown" {{ $question->question_type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                            <option value="date_time" {{ $question->question_type === 'date_time' ? 'selected' : '' }}>Date/Time</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block mb-1 text-sm text-gray-700">Required</label>
                                        <div class="flex items-center h-full pt-2">
                                            <input type="checkbox" name="questions[{{ $index }}][is_required]" value="1" {{ $question->is_required ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-700">This question requires an answer</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="options-container" style="{{ in_array($question->question_type, ['single_choice', 'multiple_choice', 'dropdown', 'rating']) ? 'display:block' : 'display:none' }}">
                                    <div class="mb-3">
                                        <div class="flex justify-between items-center mb-1">
                                            <label class="block text-sm text-gray-700">Options</label>
                                            <button type="button" class="add-option px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300">
                                                Add Option
                                            </button>
                                        </div>
                                        <div class="options-list space-y-2">
                                            @foreach($question->options as $optionIndex => $option)
                                                <div class="option-item flex items-center">
                                                    <input type="text" name="questions[{{ $index }}][options][]" value="{{ $option }}" required
                                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <button type="button" class="remove-option ml-2 text-red-600 hover:text-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all users checkbox
            const assignAllCheckbox = document.getElementById('assign-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            
            assignAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = assignAllCheckbox.checked;
                });
            });
            
            // Add question button
            const addQuestionBtn = document.getElementById('add-question');
            const questionsContainer = document.getElementById('questions-container');
            
            addQuestionBtn.addEventListener('click', function() {
                const questionIndex = document.querySelectorAll('.question-item').length;
                addQuestion(questionIndex);
            });
            
            // Initialize existing questions
            document.querySelectorAll('.question-item').forEach(questionItem => {
                // Add event listeners to question type selects
                const questionType = questionItem.querySelector('.question-type');
                const optionsContainer = questionItem.querySelector('.options-container');
                
                questionType.addEventListener('change', function() {
                    updateOptionsVisibility(this.value, optionsContainer);
                });
                
                // Add event listeners to add option buttons
                const addOptionBtn = questionItem.querySelector('.add-option');
                const optionsList = questionItem.querySelector('.options-list');
                
                addOptionBtn.addEventListener('click', function() {
                    const questionIndex = Array.from(questionsContainer.children).indexOf(questionItem);
                    addOption(questionIndex, optionsList);
                });
                
                // Add event listeners to remove question buttons
                const removeQuestionBtn = questionItem.querySelector('.remove-question');
                
                removeQuestionBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.question-item').length > 1) {
                        questionItem.remove();
                        updateQuestionNumbers();
                    } else {
                        alert('You must have at least one question.');
                    }
                });
                
                // Add event listeners to remove option buttons
                questionItem.querySelectorAll('.remove-option').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const optionsList = btn.closest('.options-list');
                        if (optionsList.querySelectorAll('.option-item').length > 1) {
                            btn.closest('.option-item').remove();
                        } else {
                            alert('You must have at least one option.');
                        }
                    });
                });
            });
            
            // Function to add a new question
            function addQuestion(questionIndex) {
                const questionNode = document.createElement('div');
                questionNode.className = 'question-item border border-gray-300 rounded-md p-4';
                questionNode.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-medium">Question <span class="question-number">${questionIndex + 1}</span></h3>
                        <button type="button" class="remove-question text-red-600 hover:text-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block mb-1 text-sm text-gray-700">Question Text</label>
                        <input type="text" name="questions[${questionIndex}][question_text]" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-3 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">Question Type</label>
                            <select name="questions[${questionIndex}][question_type]" class="question-type w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="single_choice">Single Choice</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="open_ended">Open Ended</option>
                                <option value="rating">Rating Scale</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="date_time">Date/Time</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">Required</label>
                                                            <div class="flex items-center h-full pt-2">
                                    <input id="is_required" type="checkbox" name="questions[${questionIndex}][is_required]" value="1" checked
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_required" class="ml-2 block text-sm text-gray-700">Required</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="options-container" style="display: block;">
                        <label class="block mb-1 text-sm text-gray-700">Options</label>
                        <div class="options-list space-y-2 mb-2">
                            <div class="option-item flex items-center">
                                <input type="text" name="questions[${questionIndex}][options][]" required
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" class="remove-option ml-2 text-red-600 hover:text-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="add-option px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Add Option
                        </button>
                    </div>
                `;
                
                const questionsContainer = document.getElementById('questions-container');
                questionsContainer.appendChild(questionNode);
                
                // Add event listeners
                const questionType = questionNode.querySelector('.question-type');
                const optionsContainer = questionNode.querySelector('.options-container');
                
                questionType.addEventListener('change', function() {
                    updateOptionsVisibility(this.value, optionsContainer);
                });
                
                // Add event listeners to add option buttons
                const addOptionBtn = questionNode.querySelector('.add-option');
                const optionsList = questionNode.querySelector('.options-list');
                
                addOptionBtn.addEventListener('click', function() {
                    addOption(questionIndex, optionsList);
                });
                
                // Add event listeners to remove question buttons
                const removeQuestionBtn = questionNode.querySelector('.remove-question');
                
                removeQuestionBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.question-item').length > 1) {
                        questionNode.remove();
                        updateQuestionNumbers();
                    } else {
                        alert('You must have at least one question.');
                    }
                });
                
                // Set initial options visibility based on question type
                updateOptionsVisibility(questionType.value, optionsContainer);
            }
            
            // Function to add a new option to a question
            function addOption(questionIndex, optionsList) {
                const optionItem = document.createElement('div');
                optionItem.className = 'option-item flex items-center';
                optionItem.innerHTML = `
                    <input type="text" name="questions[${questionIndex}][options][]" required
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" class="remove-option ml-2 text-red-600 hover:text-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;
                
                // Add event listener to remove option button
                const removeBtn = optionItem.querySelector('.remove-option');
                removeBtn.addEventListener('click', function() {
                    if (optionsList.querySelectorAll('.option-item').length > 1) {
                        optionItem.remove();
                    } else {
                        alert('You must have at least one option.');
                    }
                });
                
                optionsList.appendChild(optionItem);
            }
            
            // Function to update options visibility based on question type
            function updateOptionsVisibility(questionType, optionsContainer) {
                if (['single_choice', 'multiple_choice', 'dropdown', 'rating'].includes(questionType)) {
                    optionsContainer.style.display = 'block';
                } else {
                    optionsContainer.style.display = 'none';
                }
            }
            
            // Function to update question numbers after removal
            function updateQuestionNumbers() {
                const questionItems = document.querySelectorAll('.question-item');
                questionItems.forEach((item, index) => {
                    item.querySelector('.question-number').textContent = index + 1;
                });
            }
            
            // Handle "Select All Users" checkbox
            const selectAllCheckbox = document.getElementById('assign-all');
            const userCheckboxes = document.querySelectorAll('input[name="user_ids[]"]');
            
            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
</x-dashboard-layout>