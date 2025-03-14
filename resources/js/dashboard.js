// Toggle dropdown menus
document.addEventListener('DOMContentLoaded', function() {
    // User dropdown toggle
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function() {
            userDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
    
    // Sidebar submenu toggles
    const submenuToggles = document.querySelectorAll('[data-collapse-toggle]');
    
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-collapse-toggle');
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.classList.toggle('hidden');
                
                // Log for debugging
                console.log('Toggled submenu:', targetId);
            } else {
                console.error('Target element not found:', targetId);
            }
        });
        
        // Log for debugging
        console.log('Found submenu toggle for:', toggle.getAttribute('data-collapse-toggle'));
    });
}); 