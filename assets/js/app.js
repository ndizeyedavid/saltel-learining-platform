// Trainee Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    initializeHoursChart();
    initializePerformanceChart();
    
    // Initialize Interactive Elements
    initializeCalendar();
    initializeTodoList();
    initializeResourceButtons();
});

// Hours Spent Bar Chart
function initializeHoursChart() {
    const ctx = document.getElementById('hoursChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Study',
                data: [20, 35, 30, 45, 40, 50],
                backgroundColor: '#3B82F6',
                borderRadius: 4,
                barThickness: 20
            }, {
                label: 'Online Test',
                data: [15, 25, 20, 30, 25, 35],
                backgroundColor: '#D1D5DB',
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 60,
                    ticks: {
                        stepSize: 20,
                        callback: function(value) {
                            return value + 'H';
                        }
                    }
                }
            }
        }
    });
}

// Performance Doughnut Chart
function initializePerformanceChart() {
    const ctx = document.getElementById('performanceChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [89.66, 10.34],
                backgroundColor: ['#3B82F6', '#E5E7EB'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        }
    });
}

// Calendar Navigation
function initializeCalendar() {
    const prevBtn = document.querySelector('.calendar-prev');
    const nextBtn = document.querySelector('.calendar-next');
    const monthYear = document.querySelector('.calendar-month');
    
    if (prevBtn && nextBtn && monthYear) {
        let currentMonth = 5; // June (0-indexed)
        let currentYear = 2024;
        
        const months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        
        prevBtn.addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            monthYear.textContent = `${months[currentMonth]} ${currentYear}`;
        });
        
        nextBtn.addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            monthYear.textContent = `${months[currentMonth]} ${currentYear}`;
        });
    }
}

// Todo List Functionality
function initializeTodoList() {
    const checkboxes = document.querySelectorAll('#todoList input[type="checkbox"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskText = this.nextElementSibling.querySelector('p');
            if (this.checked) {
                taskText.classList.add('line-through', 'text-gray-500');
            } else {
                taskText.classList.remove('line-through', 'text-gray-500');
            }
        });
    });
}

// Resource Buttons
function initializeResourceButtons() {
    const resourceButtons = document.querySelectorAll('.resource-btn');
    
    resourceButtons.forEach(button => {
        button.addEventListener('click', function() {
            const resourceName = this.closest('.resource-item').querySelector('p').textContent;
            
            // Show toast notification
            if (typeof toastr !== 'undefined') {
                toastr.info(`Opening ${resourceName}...`);
            }
            
            // Simulate opening resource
            setTimeout(() => {
                if (typeof toastr !== 'undefined') {
                    toastr.success(`${resourceName} opened successfully!`);
                }
            }, 1000);
        });
    });
}

// Progress Animation
function animateProgress() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 100);
    });
}

// Join Class Button Functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('join-class-btn')) {
        const className = e.target.closest('.lesson-item').querySelector('h4').textContent;
        
        if (typeof toastr !== 'undefined') {
            toastr.success(`Joining ${className}...`);
        }
        
        // Simulate joining class
        setTimeout(() => {
            if (typeof toastr !== 'undefined') {
                toastr.info('Redirecting to virtual classroom...');
            }
        }, 1500);
    }
});

// Smooth scroll for navigation
function smoothScroll(target) {
    document.querySelector(target).scrollIntoView({
        behavior: 'smooth'
    });
}

// Initialize animations on page load
window.addEventListener('load', function() {
    animateProgress();
});