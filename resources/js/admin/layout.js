window.toggleAdminDropdown = function toggleAdminDropdown() {
	const menu = document.getElementById('adminDropdownMenu');
	if (menu) menu.classList.toggle('show');

	const notifDropdown = document.getElementById('notificationDropdown');
	if (notifDropdown && notifDropdown.classList.contains('show')) {
		notifDropdown.classList.remove('show');
	}
};

window.toggleNotificationDropdown = function toggleNotificationDropdown() {
	const dropdown = document.getElementById('notificationDropdown');
	if (dropdown) dropdown.classList.toggle('show');

	const adminMenu = document.getElementById('adminDropdownMenu');
	if (adminMenu && adminMenu.classList.contains('show')) {
		adminMenu.classList.remove('show');
	}
};

window.addEventListener('click', function (e) {
	if (!e.target.matches('.admin-dropdown__btn') && !e.target.closest('.admin-dropdown__btn')) {
		const menu = document.getElementById('adminDropdownMenu');
		if (menu && menu.classList.contains('show')) {
			menu.classList.remove('show');
		}
	}

	if (!e.target.matches('.notification-bell__btn') && !e.target.closest('.notification-bell')) {
		const notifDropdown = document.getElementById('notificationDropdown');
		if (notifDropdown && notifDropdown.classList.contains('show')) {
			notifDropdown.classList.remove('show');
		}
	}
});

window.addEventListener('admin-profile-updated', function (event) {
	const newAvatarUrl = event.detail.avatarUrl;
	document.querySelectorAll('.admin-avatar').forEach(img => {
		img.src = newAvatarUrl;
	});

	const placeholder = document.querySelector('.admin-avatar-placeholder');
	if (placeholder && newAvatarUrl) {
		const img = document.createElement('img');
		img.src = newAvatarUrl;
		img.alt = 'Avatar';
		img.className = 'admin-avatar';
		placeholder.replaceWith(img);
	}
});
