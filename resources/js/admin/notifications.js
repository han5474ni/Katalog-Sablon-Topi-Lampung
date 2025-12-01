// Real-time notification polling for admin
let adminLastNotificationCount = 0;

function updateAdminNotificationBadge() {
	fetch('/api/notifications/unread-count')
		.then(response => response.json())
		.then(data => {
			const badge = document.querySelector('.notification-bell .notification-badge');
			const count = data.count || 0;

			if (count > 0) {
				if (!badge) {
					const bellBtn = document.querySelector('.notification-bell__btn');
					if (bellBtn) {
						const newBadge = document.createElement('span');
						newBadge.className = 'notification-badge';
						newBadge.textContent = count;
						bellBtn.appendChild(newBadge);
					}
				} else {
					badge.textContent = count;
				}

				// Show toast if new notifications arrived
				if (count > adminLastNotificationCount && adminLastNotificationCount !== 0) {
					showNotificationToast('Anda memiliki notifikasi baru!');
				}
			} else if (badge) {
				badge.remove();
			}

			adminLastNotificationCount = count;

			// Update notification count in dropdown header
			const countElement = document.querySelector('.notification-dropdown .notification-count');
			if (countElement) {
				countElement.textContent = count + ' baru';
			}
		})
		.catch(error => console.error('Error updating notification badge:', error));
}

function showNotificationToast(message) {
	// Create toast element if it doesn't exist
	let toast = document.getElementById('notification-toast');
	if (!toast) {
		toast = document.createElement('div');
		toast.id = 'notification-toast';
		toast.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; display: none; font-size: 14px;';
		document.body.appendChild(toast);
	}

	toast.textContent = message;
	toast.style.display = 'block';

	setTimeout(() => {
		toast.style.display = 'none';
	}, 3000);
}

// Poll every 30 seconds
if (typeof window.adminNotificationInterval === 'undefined') {
	window.adminNotificationInterval = setInterval(updateAdminNotificationBadge, 30000);
	// Initial call
	updateAdminNotificationBadge();
}
