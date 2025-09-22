// Theme Switcher with localStorage persistence
class ThemeSwitcher {
    constructor() {
        this.currentTheme = 'light';
        this.init();
    }

    init() {
        // Load saved theme from localStorage or default to light
        this.loadTheme();
        
        // Apply theme on page load
        this.applyTheme();
        
        // Set up theme toggle button
        this.setupToggleButton();
        
        // Update button text and icon
        this.updateToggleButton();
    }

    loadTheme() {
        const savedTheme = localStorage.getItem('saltel-theme');
        if (savedTheme && (savedTheme === 'light' || savedTheme === 'dark')) {
            this.currentTheme = savedTheme;
        } else {
            // Check system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                this.currentTheme = 'dark';
            } else {
                this.currentTheme = 'light';
            }
        }
    }

    saveTheme() {
        localStorage.setItem('saltel-theme', this.currentTheme);
    }

    applyTheme() {
        const html = document.documentElement;
        
        if (this.currentTheme === 'dark') {
            html.classList.add('dark');
            html.classList.remove('light');
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
        }
        
        // Save theme to localStorage
        this.saveTheme();
        
        // Update button appearance
        this.updateToggleButton();
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: this.currentTheme } 
        }));
    }

    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme();
        
        // Show notification
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Switched to ${this.currentTheme} mode`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }
    }

    setupToggleButton() {
        const toggleButton = document.getElementById('themeToggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                this.toggleTheme();
            });
        }
    }

    updateToggleButton() {
        const toggleButton = document.getElementById('themeToggle');
        if (toggleButton) {
            const icon = toggleButton.querySelector('i');
            const text = toggleButton.querySelector('span');
            
            if (this.currentTheme === 'dark') {
                if (icon) icon.className = 'w-5 fas fa-sun';
                if (text) text.textContent = 'Light Mode';
                toggleButton.classList.remove('hover:bg-blue-50', 'hover:text-blue-600');
                toggleButton.classList.add('hover:bg-yellow-50', 'hover:text-yellow-600');
            } else {
                if (icon) icon.className = 'w-5 fas fa-moon';
                if (text) text.textContent = 'Dark Mode';
                toggleButton.classList.remove('hover:bg-yellow-50', 'hover:text-yellow-600');
                toggleButton.classList.add('hover:bg-blue-50', 'hover:text-blue-600');
            }
        }
    }

    getCurrentTheme() {
        return this.currentTheme;
    }
}

// Initialize theme switcher when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.themeSwitcher = new ThemeSwitcher();
});

// Listen for system theme changes
if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (!localStorage.getItem('saltel-theme')) {
            window.themeSwitcher.currentTheme = e.matches ? 'dark' : 'light';
            window.themeSwitcher.applyTheme();
        }
    });
}
