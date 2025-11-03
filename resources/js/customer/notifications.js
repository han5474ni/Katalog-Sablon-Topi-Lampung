// Customer notifications functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification system
    initializeNotifications();

    // Check for new notifications every 30 seconds
    setInterval(checkNotifications, 30000);
});

function initializeNotifications() {
    const notificationBell = document.querySelector('.notification-bell');
    const notificationBadge = document.querySelector('.notification-badge');

    if (notificationBell && notificationBadge) {
        // Add click handler for notification bell
        notificationBell.addEventListener('click', toggleNotificationDropdown);

        // Load initial notifications
        loadNotifications();
    }
}

function toggleNotificationDropdown() {
    // This would show/hide a notification dropdown
    // For now, just log to console
    console.log('Notification bell clicked');
}

async function checkNotifications() {
    try {
        const response = await fetch('/api/customer/notifications/check', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            const data = await response.json();
            updateNotificationBadge(data.unread_count);
        }
    } catch (error) {
        console.error('Error checking notifications:', error);
    }
}

async function loadNotifications() {
    try {
        const response = await fetch('/api/customer/notifications', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            const data = await response.json();
            updateNotificationBadge(data.unread_count);
            // Here you would populate the notification dropdown
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function updateNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');

    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Function to show success/error messages
function showMessage(message, type = 'success') {
    // Create message element
    const messageEl = document.createElement('div');
    messageEl.className = `alert alert-${type}`;
    messageEl.textContent = message;

    // Add to page
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(messageEl, container.firstChild);

    // Remove after 5 seconds
    setTimeout(() => {
        messageEl.remove();
    }, 5000);
}

// Export functions for global use
window.CustomerNotifications = {
    showMessage,
    updateNotificationBadge,
    checkNotifications
};
