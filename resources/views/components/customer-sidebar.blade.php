@props(['active' => 'dashboard'])

<!-- Sidebar -->
<div class="w-64 bg-navy-900 text-white p-6 flex flex-col">
    <div class="mb-8">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LGI STORE" class="h-8">
        </a>
    </div>
    
    <nav class="flex-1">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center p-3 rounded-lg mb-2 {{ $active === 'dashboard' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
            <span class="material-icons mr-3">home</span>
            Dashboard
        </a>
        <a href="{{ route('keranjang') }}" 
           class="flex items-center p-3 rounded-lg mb-2 {{ $active === 'keranjang' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
            <span class="material-icons mr-3">shopping_cart</span>
            Keranjang
        </a>
        <a href="{{ route('order-list') }}" 
           class="flex items-center p-3 rounded-lg mb-2 {{ $active === 'order-list' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
            <span class="material-icons mr-3">list_alt</span>
            Order List
        </a>
        <a href="{{ route('chatbot') }}" 
           class="flex items-center p-3 rounded-lg mb-2 {{ $active === 'chatbot' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
            <span class="material-icons mr-3">chat</span>
            Chatbot
        </a>
        <a href="{{ route('profile') }}" 
           class="flex items-center p-3 rounded-lg mb-2 {{ $active === 'profile' ? 'bg-yellow-400 text-navy-900' : 'text-white hover:bg-navy-800' }}">
            <span class="material-icons mr-3">person</span>
            Profile
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button type="submit" class="w-full bg-yellow-400 text-navy-900 p-3 rounded-lg flex items-center justify-center font-semibold hover:bg-yellow-500 transition">
            <span class="material-icons mr-2">logout</span>
            Log Out
        </button>
    </form>
</div>
