// Designer Appointments JavaScript Enhancements

document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh meeting status every minute
    setInterval(updateMeetingStatus, 60000);
    
    // Initialize tooltips
    initializeTooltips();
    
    // Add click animations to meeting buttons
    addMeetingButtonAnimations();
    
    // Add real-time countdown timers
    addCountdownTimers();
});

function updateMeetingStatus() {
    // Update meeting button states based on current time
    const meetingButtons = document.querySelectorAll('.meeting-btn');
    meetingButtons.forEach(button => {
        const appointmentCard = button.closest('.appointment-card');
        const timeElement = appointmentCard.querySelector('.appointment-time');
        
        if (timeElement && timeElement.dataset.datetime) {
            try {
                const appointmentTime = new Date(timeElement.dataset.datetime);
                const now = new Date();
                const canJoin = now >= new Date(appointmentTime.getTime() - 5 * 60000); // 5 minutes before
                
                if (canJoin && !button.classList.contains('active')) {
                    button.classList.add('active');
                    button.innerHTML = '<i class="fas fa-video me-1"></i>Join Meeting';
                }
            } catch (error) {
                console.warn('Error parsing appointment time:', error);
            }
        }
    });
}

function initializeTooltips() {
    // Initialize Bootstrap tooltips for meeting information
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function addMeetingButtonAnimations() {
    const meetingButtons = document.querySelectorAll('.meeting-btn');
    
    meetingButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

function addCountdownTimers() {
    const appointmentCards = document.querySelectorAll('.appointment-card');
    
    appointmentCards.forEach(card => {
        const timeElement = card.querySelector('.appointment-time');
        if (timeElement && timeElement.dataset.datetime) {
            const appointmentTime = new Date(timeElement.dataset.datetime);
            const now = new Date();
            
            if (appointmentTime > now) {
                const countdownElement = document.createElement('div');
                countdownElement.className = 'countdown-timer text-muted small';
                countdownElement.innerHTML = '<i class="fas fa-clock me-1"></i><span class="countdown-text"></span>';
                
                const timeContainer = card.querySelector('.text-muted.small');
                if (timeContainer) {
                    timeContainer.appendChild(countdownElement);
                }
                
                updateCountdown(countdownElement, appointmentTime);
                setInterval(() => updateCountdown(countdownElement, appointmentTime), 1000);
            }
        }
    });
}

function updateCountdown(element, targetTime) {
    const now = new Date();
    const diff = targetTime - now;
    
    if (diff <= 0) {
        element.innerHTML = '<i class="fas fa-video me-1"></i>Meeting Ready!';
        element.className = 'countdown-timer text-success small fw-bold';
        return;
    }
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    let timeString = '';
    if (hours > 0) {
        timeString += hours + 'h ';
    }
    if (minutes > 0) {
        timeString += minutes + 'm ';
    }
    timeString += seconds + 's';
    
    element.querySelector('.countdown-text').textContent = timeString;
}

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .meeting-btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .countdown-timer {
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    .meeting-btn.active {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
        50% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.8); }
        100% { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
    }
`;
document.head.appendChild(style);
