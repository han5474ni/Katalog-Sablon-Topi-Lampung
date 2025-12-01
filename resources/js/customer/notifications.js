// Customer notification dropdown toggle
window.toggleCustomerNotificationDropdown = function toggleCustomerNotificationDropdown() {
	const dropdown = document.getElementById('customerNotificationDropdown');
	if (dropdown) {
		dropdown.classList.toggle('hidden');
	}
};

// Close dropdown when clicking outside
document.addEventListener('click', function (e) {
	const dropdown = document.getElementById('customerNotificationDropdown');
	const bellWrapper = document.querySelector('.notification-bell-wrapper');

	if (dropdown && bellWrapper && !bellWrapper.contains(e.target)) {
		dropdown.classList.add('hidden');
	}
});

// Real-time notification polling for customer
let customerLastNotificationCount = 0;

function updateCustomerNotificationBadge() {
	fetch('/api/notifications/unread-count')
		.then(response => response.json())
		.then(data => {
			const badge = document.querySelector('.notification-bell-wrapper .notification-badge');
			const count = data.count || 0;

			if (count > 0) {
				if (!badge) {
					const bellBtn = document.querySelector('.notification-bell');
					if (bellBtn) {
						const newBadge = document.createElement('span');
						newBadge.className = 'notification-badge absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center';
						newBadge.textContent = count;
						bellBtn.appendChild(newBadge);
					}
				} else {
					badge.textContent = count;
					badge.classList.remove('hidden');
				}

				// Show toast if new notifications arrived
				if (count > customerLastNotificationCount && customerLastNotificationCount !== 0) {
					showCustomerNotificationToast('Anda memiliki notifikasi baru!');
				}
			} else if (badge) {
				badge.classList.add('hidden');
			}

			customerLastNotificationCount = count;

			// Update notification count in dropdown header
			const countElement = document.querySelector('.customer-notification-dropdown .text-xs.text-gray-500');
			if (countElement) {
				countElement.textContent = count + ' baru';
			}
		})
		.catch(error => console.error('Error updating customer notification badge:', error));
}

function showCustomerNotificationToast(message) {
	// Create toast element if it doesn't exist
	let toast = document.getElementById('customer-notification-toast');
	if (!toast) {
		toast = document.createElement('div');
		toast.id = 'customer-notification-toast';
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
if (typeof window.customerNotificationInterval === 'undefined') {
	window.customerNotificationInterval = setInterval(updateCustomerNotificationBadge, 30000);
	// Initial call
	updateCustomerNotificationBadge();
}
