@props(['title' => 'Dashboard'])

<!-- Header -->
<header class="bg-white p-4 shadow flex justify-between items-center">
    <div class="flex items-center text-sm">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition" aria-label="Kembali ke Homepage">Beranda</a>
        <li class="mx-2 text-gray-400"><i class="fas fa-chevron-right text-xs"></i></li>
        <span class="text-gray-700 font-medium">{{ $title }}</span>
    </div>
    <div class="flex items-center">
        <div class="flex items-center">
            <!-- Notification Bell -->
            <button class="relative p-2 hover:bg-gray-100 rounded-full transition-colors" aria-label="Notifikasi">
                <span class="material-icons text-gray-600 text-2xl">notifications</span>
                <!-- Notification Badge -->
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
        </div>
    </div>
</header>
