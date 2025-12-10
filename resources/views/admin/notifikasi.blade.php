<x-admin-layout>
    @vite(['resources/css/admin/notifikasi.css'])

    <div class="admin-notification-container">
        <!-- Actions Bar -->
        <div class="notification-actions-bar">
            <div class="notification-actions-content">
                <label class="select-all-wrapper">
                    <input type="checkbox" id="selectAll" class="select-all-checkbox">
                    <span class="select-all-label">Pilih semua</span>
                </label>
                
                <div class="notification-filters-actions">
                    <div class="notification-filters">
                        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}" 
                           class="filter-btn {{ $filter === 'all' ? 'active' : '' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}" 
                           class="filter-btn {{ $filter === 'unread' ? 'active' : '' }}">
                            Belum Dibaca ({{ $unreadCount }})
                        </a>
                        <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}" 
                           class="filter-btn {{ $filter === 'read' ? 'active' : '' }}">
                            Sudah Dibaca
                        </a>
                    </div>
                    
                    <button id="markReadBtn" class="mark-read-btn">
                        <i class="fas fa-check-double"></i>
                        <span>Tandai sudah dibaca</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Notification List -->
        <div class="admin-notification-list" id="notificationList">
            @forelse($notifications as $notification)
            <div class="admin-notification-card {{ $notification->read_at ? 'read' : 'unread' }}" 
                 data-id="{{ $notification->id }}">
                <div class="notification-card-content">
                    <div class="notification-checkbox-wrapper">
                        <input type="checkbox" 
                               class="notification-item-checkbox notification-checkbox" 
                               value="{{ $notification->id }}">
                    </div>
                    
                    <div class="notification-icon-wrapper {{ $notification->type === 'new_order' || $notification->type === 'new_order_admin' ? 'type-order' : ($notification->type === 'va_activated' || $notification->type === 'payment_received' ? 'type-payment' : 'type-chat') }}">
                        <i class="fas fa-{{ $notification->type === 'new_order' || $notification->type === 'new_order_admin' ? 'shopping-cart' : ($notification->type === 'va_activated' || $notification->type === 'payment_received' ? 'credit-card' : 'comment') }}"></i>
                    </div>
                    
                    <div class="notification-content">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <p class="notification-message">{{ $notification->message }}</p>
                        <span class="notification-time">
                            <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    
                    @if($notification->action_url)
                    <a href="{{ $notification->action_url }}" 
                       class="notification-action-btn">
                        <i class="fas fa-arrow-right"></i> {{ $notification->action_text ?? 'Lihat Detail' }}
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="notification-empty-state">
                <i class="fas fa-bell-slash notification-empty-icon"></i>
                <p class="notification-empty-text">Belum ada notifikasi</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="notification-pagination">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const notificationCheckboxes = document.querySelectorAll('.notification-checkbox');
            const markReadBtn = document.getElementById('markReadBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Select All Functionality
            selectAllCheckbox?.addEventListener('change', function() {
                notificationCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Update Select All state when individual checkboxes change
            notificationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(notificationCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(notificationCheckboxes).some(cb => cb.checked);
                    
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });

            // Mark as Read Functionality
            markReadBtn?.addEventListener('click', async function() {
                const selectedNotifications = Array.from(notificationCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedNotifications.length === 0) {
                    showToast('Pilih notifikasi terlebih dahulu', 'error');
                    return;
                }

                try {
                    // Mark each notification as read
                    for (const notificationId of selectedNotifications) {
                        await fetch(`/admin/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                    }

                    showToast('Notifikasi berhasil ditandai sudah dibaca', 'success');
                    
                    // Reload after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } catch (error) {
                    console.error('Error marking notifications as read:', error);
                    showToast('Terjadi kesalahan saat menandai notifikasi', 'error');
                }
            });

            // Toast notification
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `notification-toast ${type}`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
    @endpush
</x-admin-layout>
