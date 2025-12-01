@props(['title' => 'Dashboard', 'active' => 'dashboard'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite('resources/css/customer/shared.css')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-navy-900 text-white p-6 flex flex-col sticky top-0 h-screen overflow-y-auto">
            <div class="mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-lgi-Photoroom.png') }}" alt="LGI Store Logo" class="h-10">
                    <div>
                        <div class="font-bold text-sm">LGI STORE</div>
                        <div class="text-xs text-gray-300">PEDULI KUALITAS</div>
                    </div>
                </a>
            </div>
            
            <nav class="flex-1 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center p-3 rounded-lg {{ $active === 'dashboard' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="material-icons mr-3">home</span>
                    Dashboard
                </a>
                <a href="{{ route('keranjang') }}" 
                   class="flex items-center p-3 rounded-lg {{ $active === 'keranjang' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="material-icons mr-3">shopping_cart</span>
                    Keranjang
                </a>
                <a href="{{ route('order-list') }}"
                   class="flex items-center p-3 rounded-lg {{ $active === 'order-list' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="material-icons mr-3">list_alt</span>
                    Daftar Pesanan
                </a>
                <a href="{{ route('custom-design') }}"
                   class="flex items-center p-3 rounded-lg {{ $active === 'custom-design' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="material-icons mr-3">palette</span>
                    Desain Kustom
                </a>
                <a href="{{ route('chatpage') }}"
                   class="flex items-center p-3 rounded-lg {{ $active === 'chatpage' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="material-icons mr-3">chat</span>
                    Chatbot
                </a>
                <a href="{{ route('notifikasi') }}"
                   class="flex items-center justify-between p-3 rounded-lg {{ $active === 'notifikasi' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
                    <span class="flex items-center">
                        <span class="material-icons mr-3">notifications</span>
                        Notifikasi
                    </span>
                    @php
                        $unreadCount = auth()->check() ? app(\App\Services\NotificationService::class)->getUnreadCount(auth()->id()) : 0;
                    @endphp
                    @if($unreadCount > 0)
                    <span class="notification-badge text-xs font-semibold px-2 py-0.5 rounded-full {{ $active === 'notifikasi' ? 'bg-navy-900 text-yellow-300' : 'bg-yellow-400 text-navy-900' }}">
                        {{ $unreadCount }}
                    </span>
                    @else
                    <span class="notification-badge text-xs font-semibold px-2 py-0.5 rounded-full {{ $active === 'notifikasi' ? 'bg-navy-900 text-yellow-300' : 'bg-yellow-400 text-navy-900' }} hidden">
                        0
                    </span>
                    @endif
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="border-t border-navy-700 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 rounded-lg text-white hover:bg-red-600 transition">
                        <span class="material-icons mr-3">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="p-4 flex justify-between items-center">
                    <div class="flex items-center text-sm">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition">Beranda</a>
                        <span class="mx-2 text-gray-400"><i class="fas fa-chevron-right text-xs"></i></span>
                        <span class="text-gray-700 font-medium">{{ $title }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Notification Bell -->
                        <div class="relative notification-bell-wrapper">
                            <button class="notification-bell relative p-2 hover:bg-gray-100 rounded-full transition-colors" onclick="toggleCustomerNotificationDropdown()" aria-label="Notifikasi">
                                <i class="fas fa-bell text-gray-600 text-xl"></i>
                                @php
                                    $customerUnreadCount = auth()->check() ? app(\App\Services\NotificationService::class)->getUnreadCount(auth()->id()) : 0;
                                @endphp
                                @if($customerUnreadCount > 0)
                                <span class="notification-badge absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $customerUnreadCount }}</span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div class="customer-notification-dropdown hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50" id="customerNotificationDropdown">
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-base font-semibold text-gray-800">Notifikasi</h3>
                                        <span class="text-xs text-gray-500">{{ $customerUnreadCount }} baru</span>
                                    </div>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @php
                                        $customerNotifications = auth()->check() ? app(\App\Services\NotificationService::class)->getUserNotifications(auth()->id(), 5) : collect();
                                    @endphp
                                    @forelse($customerNotifications as $notification)
                                    <a href="{{ $notification->type === 'order_approved' || $notification->type === 'order_rejected' || $notification->type === 'order_status_updated' ? route('order-detail', ['type' => 'regular', 'id' => $notification->notifiable_id]) : ($notification->type === 'custom_order_approved' || $notification->type === 'custom_order_rejected' ? route('custom-design.tracking', $notification->notifiable_id) : route('chat')) }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $notification->type === 'order_approved' || $notification->type === 'custom_order_approved' ? 'bg-green-100' : ($notification->type === 'order_rejected' || $notification->type === 'custom_order_rejected' ? 'bg-red-100' : 'bg-blue-100') }}">
                                                <i class="fas fa-{{ $notification->type === 'order_approved' || $notification->type === 'custom_order_approved' ? 'check-circle' : ($notification->type === 'order_rejected' || $notification->type === 'custom_order_rejected' ? 'times-circle' : ($notification->type === 'chat_reply' ? 'comment' : 'box')) }} text-{{ $notification->type === 'order_approved' || $notification->type === 'custom_order_approved' ? 'green' : ($notification->type === 'order_rejected' || $notification->type === 'custom_order_rejected' ? 'red' : 'blue') }}-600"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($notification->message, 60) }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="px-4 py-8 text-center">
                                        <i class="fas fa-bell-slash text-gray-300 text-3xl mb-2"></i>
                                        <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                    </div>
                                    @endforelse
                                </div>
                                <a href="{{ route('notifikasi') }}" class="block p-3 text-center text-sm text-blue-600 hover:bg-gray-50 font-medium border-t border-gray-200">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="h-8 w-8 rounded-full header-avatar">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user-circle mr-2"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-b-lg">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @vite('resources/js/customer/notifications.js')
    @stack('scripts')
</body>
</html>
