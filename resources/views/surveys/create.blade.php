<x-dashboard-layout>
    <x-slot name="title">Create Survey</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-6">Create New Survey</h1>
            
            <form method="POST" action="{{ route('surveys.store') }}" id="survey-form">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4 flex space-x-4">
                    <div class="flex items-center">
                        <input id="is_mandatory" type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_mandatory" class="ml-2 block text-sm text-gray-700">Mandatory</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>
                
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date (Optional)</label>
                        <input id="start_date" type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">End Date (Optional)</label>
                        <input id="end_date" type="datetime-local" name="end_date" value="{{ old('end_date') }}"
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
                                           {{ in_array($user->id, old('users', [])) ? 'checked' : '' }}>
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
                    <h2 class="text-xl font-semibold mb-4">Survey Questions</h2>
                    
                    <div id="questions-container">
                        <!-- Questions will be added here dynamically -->
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" id="add-question" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Add Question
                        </button>
                    </div>
                    
                    @error('questions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('surveys.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-2">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Survey</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Question template (hidden) -->
    <template id="question-template">
        <div class="question-item bg-gray-50 p-4 rounded-md mb-4">
            <div class="flex justify-between mb-2">
                <h3 class="text-lg font-medium">Question <span class="question-number"></span></h3>
                <button type="button" class="remove-question text-red-600 hover:text-red-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="block mb-1 text-sm font-medium text-gray-700">Question Text</label>
                <input type="text" name="questions[INDEX][question_text]" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-3">
                <label class="block mb-1 text-sm font-medium text-gray-700">Question Type</label>
                <select name="questions[INDEX][question_type]" class="question-type w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="single_choice">Single Choice (Radio Buttons)</option>
                    <option value="multiple_choice">Multiple Choice (Checkboxes)</option>
                    <option value="open_ended">Open-ended (Text Input)</option>
                    <option value="rating">Rating Scale</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="date_time">Date/Time Selection</option>
                </select>
            </div>
            
            <div class="mb-3">
                <div class="flex items-center">
                    <input type="checkbox" name="questions[INDEX][is_required]" value="1" checked
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-700">Required</label>
                </div>
            </div>
            
            <div class="options-container">
                <div class="mb-2">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Options</label>
                    <p class="text-xs text-gray-500 mb-2">Add options for this question</p>
                    
                    <div class="options-list space-y-2">
                        <div class="option-item flex items-center">
                            <input type="text" name="questions[INDEX][options][]" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="remove-option ml-2 text-red-600 hover:text-red-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" class="add-option mt-2 text-sm text-blue-600 hover:text-blue-800">
                        + Add Option
                    </button>
                </div>
            </div>
        </div>
    </template>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionsContainer = document.getElementById('questions-container');
            const questionTemplate = document.getElementById('question-template');
            const addQuestionBtn = document.getElementById('add-question');
            const assignAllCheckbox = document.getElementById('assign-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            
            let questionCount = 0;
            
            // Add first question by default
            addQuestion();
            
            // Add question button click handler
            addQuestionBtn.addEventListener('click', addQuestion);
            
            // Select all users checkbox
            assignAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = assignAllCheckbox.checked;
                });
            });
            
            // Function to add a new question
            function addQuestion() {
                const questionIndex = questionCount++;
                const questionNumber = questionIndex + 1;
                
                // Clone the template
                const questionNode = questionTemplate.content.cloneNode(true);
                
                // Update question number and indices
                questionNode.querySelector('.question-number').textContent = questionNumber;
                
                // Replace INDEX placeholder with actual index
                const elements = questionNode.querySelectorAll('[name*="INDEX"]');
                elements.forEach(element => {
                    element.name = element.name.replace('INDEX', questionIndex);
                });
                
                // Add event listeners
                const questionItem = questionNode.querySelector('.question-item');
                const questionType = questionNode.querySelector('.question-type');
                const optionsContainer = questionNode.querySelector('.options-container');
                const addOptionBtn = questionNode.querySelector('.add-option');
                const removeQuestionBtn = questionNode.querySelector('.remove-question');
                
                // Question type change handler
                questionType.addEventListener('change', function() {
                    updateOptionsVisibility(questionType.value, optionsContainer);
                });
                
                // Add option button click handler
                addOptionBtn.addEventListener('click', function() {
                    addOption(questionIndex, questionNode.querySelector('.options-list'));
                });
                
                // Remove question button click handler
                removeQuestionBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.question-item').length > 1) {
                        questionItem.remove();
                        updateQuestionNumbers();
                    } else {
                        alert('You must have at least one question.');
                    }
                });
                
                // Add event listeners to remove option buttons
                questionNode.querySelectorAll('.remove-option').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const optionsList = btn.closest('.options-list');
                        if (optionsList.querySelectorAll('.option-item').length > 1) {
                            btn.closest('.option-item').remove();
                        } else {
                            alert('You must have at least one option.');
                        }
                    });
                });
                
                // Add a second option by default for choice questions
                addOption(questionIndex, questionNode.querySelector('.options-list'));
                
                // Set initial options visibility based on question type
                updateOptionsVisibility(questionType.value, optionsContainer);
                
                // Append the new question to the container
                questionsContainer.appendChild(questionNode);
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
        });
    </script>
</x-dashboard-layout>