<x-customer-layout title="Chatbot" active="chatpage">
    @stack('styles')
    @vite(['resources/css/guest/chatpage.css'])

    <div class="chatpage-container">
        <div class="chatpage-chat">
            @livewire('chatbot-customer')
        </div>
    </div>

    @stack('scripts')
</x-customer-layout>
