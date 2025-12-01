// Notification Dropdown Handler
class NotificationDropdown {
    constructor() {
        this.bellIcon = document.getElementById('notification-bell');
        this.dropdown = document.getElementById('notification-dropdown');
        this.badge = document.getElementById('notification-badge');
        this.notificationList = document.getElementById('notification-list');
        this.markAllReadBtn = document.getElementById('mark-all-read');
        
        // Detect if admin or customer
        this.isAdmin = window.location.pathname.startsWith('/admin');
        this.baseUrl = this.isAdmin ? '/admin/notifications' : '/notifications';
        
        if (!this.bellIcon) return;
        
        this.init();
    }
    
    init() {
        // Toggle dropdown on click
        this.bellIcon.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleDropdown();
        });
        
        // Mark all as read
        if (this.markAllReadBtn) {
            this.markAllReadBtn.addEventListener('click', () => {
                this.markAllAsRead();
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-wrapper')) {
                this.closeDropdown();
            }
        });
        
        // Load notifications on page load
        this.loadNotifications();
        
        // Auto-refresh every 30 seconds
        setInterval(() => this.loadNotifications(), 30000);
    }
    
    toggleDropdown() {
        const isVisible = this.dropdown.style.display === 'block';
        
        if (isVisible) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }
    
    openDropdown() {
        this.dropdown.style.display = 'block';
        this.dropdown.classList.add('show');
        this.loadNotifications();
    }
    
    closeDropdown() {
        this.dropdown.style.display = 'none';
        this.dropdown.classList.remove('show');
    }
    
    async loadNotifications() {
        try {
            const response = await fetch(this.baseUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to load notifications');
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.updateBadge(data.unread_count);
                this.renderNotifications(data.notifications);
                this.updateMarkAllButton(data.unread_count);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showError();
        }
    }
    
    updateBadge(count) {
        if (count > 0) {
            this.badge.textContent = count > 99 ? '99+' : count;
            this.badge.style.display = 'flex';
        } else {
            this.badge.style.display = 'none';
        }
    }
    
    updateMarkAllButton(count) {
        if (count > 0) {
            this.markAllReadBtn.style.display = 'block';
        } else {
            this.markAllReadBtn.style.display = 'none';
        }
    }
    
    renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            this.showEmptyState();
            return;
        }
        
        const html = notifications.map(notif => this.renderNotification(notif)).join('');
        this.notificationList.innerHTML = html;
        
        // Attach click handlers
        this.attachNotificationHandlers();
    }
    
    renderNotification(notif) {
        const isUnread = !notif.read_at;
        const priorityClass = `priority-${notif.priority}`;
        const unreadClass = isUnread ? 'unread' : '';
        
        return `
            <div class="notification-item ${unreadClass} ${priorityClass}" data-id="${notif.id}">
                <div class="notification-icon">
                    ${this.getPriorityIcon(notif.priority)}
                </div>
                <div class="notification-content">
                    <h4 class="notification-title">${this.escapeHtml(notif.title)}</h4>
                    <p class="notification-message">${this.escapeHtml(notif.message)}</p>
                    <span class="notification-time">${notif.created_at}</span>
                </div>
                ${isUnread ? '<div class="notification-dot"></div>' : ''}
            </div>
        `;
    }
    
    getPriorityIcon(priority) {
        switch (priority) {
            case 'high':
                return '<i class="fas fa-exclamation-circle text-red"></i>';
            case 'medium':
                return '<i class="fas fa-info-circle text-yellow"></i>';
            default:
                return '<i class="fas fa-bell text-blue"></i>';
        }
    }
    
    showEmptyState() {
        this.notificationList.innerHTML = `
            <div class="notification-empty">
                <i class="fas fa-bell-slash"></i>
                <p>Tidak ada notifikasi</p>
            </div>
        `;
    }
    
    showError() {
        this.notificationList.innerHTML = `
            <div class="notification-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Gagal memuat notifikasi</p>
                <button onclick="notificationDropdown.loadNotifications()">Coba Lagi</button>
            </div>
        `;
    }
    
    attachNotificationHandlers() {
        const items = this.notificationList.querySelectorAll('.notification-item');
        
        items.forEach(item => {
            item.addEventListener('click', () => {
                const id = item.dataset.id;
                this.markAsRead(id, item);
            });
        });
    }
    
    async markAsRead(id, element) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                element.classList.remove('unread');
                const dot = element.querySelector('.notification-dot');
                if (dot) dot.remove();
                
                // Reload to update badge
                this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    async markAllAsRead() {
        try {
            const response = await fetch(`${this.baseUrl}/read-all`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                // Reload notifications
                this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.notificationDropdown = new NotificationDropdown();
});
