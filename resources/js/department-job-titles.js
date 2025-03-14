document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const jobTitleSelect = document.getElementById('job_title_id');
    
    if (departmentSelect && jobTitleSelect) {
        // Function to filter job titles based on selected department
        function filterJobTitles() {
            const selectedDepartmentId = departmentSelect.value;
            const jobTitleOptions = jobTitleSelect.querySelectorAll('option');
            
            // Reset job title select if department changes
            if (jobTitleSelect.dataset.lastDepartment && 
                jobTitleSelect.dataset.lastDepartment !== selectedDepartmentId) {
                jobTitleSelect.value = '';
            }
            
            // Store current department for future reference
            jobTitleSelect.dataset.lastDepartment = selectedDepartmentId;
            
            // Show/hide job title options based on department
            jobTitleOptions.forEach(option => {
                if (option.value === '' || !selectedDepartmentId || 
                    option.dataset.department === selectedDepartmentId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }
        
        // Initial filter
        filterJobTitles();
        
        // Filter job titles when department changes
        departmentSelect.addEventListener('change', filterJobTitles);
        
        // Alternative: Load job titles via AJAX when department changes
        departmentSelect.addEventListener('change', function() {
            const departmentId = this.value;
            
            if (departmentId) {
                fetch(`/users/job-titles?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing options except the first one
                        while (jobTitleSelect.options.length > 1) {
                            jobTitleSelect.remove(1);
                        }
                        
                        // Add new options
                        data.forEach(jobTitle => {
                            const option = document.createElement('option');
                            option.value = jobTitle.id;
                            option.textContent = jobTitle.name;
                            option.dataset.department = jobTitle.department_id;
                            jobTitleSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading job titles:', error));
            }
        });
    }
}); 