@props(['title' => 'Dashboard'])

<!-- Header -->
<header class="bg-white p-4 shadow flex justify-between items-center">
    <div class="flex items-center text-sm">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition" aria-label="Kembali ke Homepage">Home</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700 font-medium">{{ $title }}</span>
    </div>
    <div class="flex items-center">
        <div class="flex items-center gap-3 mr-4">
            <span class="relative">
                <input type="text" value="October 2025 - November 2025" readonly 
                       class="border rounded-lg pl-4 pr-10 py-2 w-64 text-sm text-gray-700 focus:outline-none cursor-default" 
                       aria-label="Rentang tanggal">
                <span class="material-icons absolute right-3 top-2 text-gray-400 text-xl">calendar_today</span>
            </span>
            
            <!-- Notification Bell -->
            <button class="relative p-2 hover:bg-gray-100 rounded-full transition-colors" aria-label="Notifikasi">
                <span class="material-icons text-gray-600 text-2xl">notifications</span>
                <!-- Notification Badge -->
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
                 alt="Foto profil" 
                 class="header-avatar w-10 h-10 rounded-full object-cover border border-gray-200">
        </div>
    </div>
</header>
