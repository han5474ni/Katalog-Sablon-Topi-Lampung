<x-admin-layout title="Chatbot">
    @push('styles')
        {{-- Pastikan path ini sesuai dengan struktur proyek Anda --}}
        @vite(['resources/css/admin/chatbot.css'])
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endpush

<div class="chatbot-container">
    {{-- Header Halaman --}}
    <header class="page-header">
        <h1 class="page-title">Chatbot</h1>
        <div class="date-range-picker">
            <i class="far fa-calendar-alt"></i>
            <span>Oktober 16, 2025 - November 11, 2025</span>
        </div>
    </header>

    {{-- Layout Utama Chat --}}
    <div class="chat-layout">

        {{-- Kolom Daftar Percakapan (Kiri) --}}
        <aside class="conversation-list">
            <div class="list-header">
                <h2 class="list-title">Percakapan</h2>
                <span class="list-count">3 Aktif</span>
            </div>
            <div class="list-body">
                {{-- Percakapan 1 (Aktif) --}}
                <div class="convo-item active">
                    <div class="convo-header">
                        <span class="convo-name">Hakiki</span>
                        <span class="convo-time">12 : 10</span>
                    </div>
                    <div class="convo-message">
                        Meminta rekomendasi jersey futsal <300
                    </div>
                    <div class="convo-tags">
                        <span class="tag tag-blue">2 baru</span>
                        <span class="tag tag-gray">New</span>
                    </div>
                </div>
                {{-- Percakapan 2 --}}
                <div class="convo-item">
                    <div class="convo-header">
                        <span class="convo-name">Blodot</span>
                        <span class="convo-time">12 : 01</span>
                    </div>
                    <div class="convo-message">
                        Status ORDER882?
                    </div>
                    <div class="convo-tags">
                        <span class="tag tag-blue">1 baru</span>
                        <span class="tag tag-gray">New</span>
                    </div>
                </div>
                {{-- Percakapan 3 --}}
                <div class="convo-item">
                    <div class="convo-header">
                        <span class="convo-name">Hani</span>
                        <span class="convo-time">11 : 56</span>
                    </div>
                    <div class="convo-message">
                        Ukuran 80 ada ?
                    </div>
                    <div class="convo-tags">
                        <span class="tag tag-blue">2 baru</span>
                        <span class="tag tag-gray">New</span>
                    </div>
                </div>
                {{-- Percakapan 4 --}}
                <div class="convo-item">
                    <div class="convo-header">
                        <span class="convo-name">Elsa</span>
                        <span class="convo-time">10 : 10</span>
                    </div>
                    <div class="convo-message">
                        Baju kuda lumping ada ?
                    </div>
                    <div class="convo-tags">
                        <span class="tag tag-blue">19 baru</span>
                        <span class="tag tag-gray">New</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Kolom Jendela Chat (Kanan) --}}
        <main class="chat-window">
            {{-- Header Jendela Chat --}}
            <header class="chat-header">
                <div class="user-info">
                    <div class="avatar">H</div>
                    <div class="user-details">
                        <span class="user-name">Hakiki</span>
                        <span class="user-status">U-83874 • Aktif</span>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="btn btn-subtle">Template Cepat</button>
                    <button class="btn btn-subtle">Kirim Template</button>
                    <button class="btn btn-dark">Handover Ke CS</button>
                </div>
            </header>

            {{-- Isi Percakapan --}}
            <div class="message-area">
                {{-- Pesan Pengguna --}}
                <div class="message-bubble user-message">
                    <div class="avatar">H</div>
                    <div class="message-content">
                        rekomendasi jersey futsal <300
                    </div>
                </div>

                {{-- Pesan Admin/Bot --}}
                <div class="message-bubble admin-message">
                    <div class="message-content">
                        Ini 3 pilihan teratas untukmu...
                    </div>
                    <div class="avatar">H</div>
                </div>
            </div>

            {{-- Input Balasan --}}
            <footer class="chat-footer">
                <div class="suggested-replies">
                    <button class="reply-chip">Tawarkan diskon 10 %</button>
                    <button class="reply-chip">Minta ukuran</button>
                    <button class="reply-chip">Minta budget</button>
                    <button class="reply-chip">Rekomendasi lagi</button>
                </div>
                <div class="message-input-area">
                    <input type="text" class="message-input" placeholder="Ketik balasan...">
                    <button class="btn btn-dark btn-send">Kirim</button>
                </div>
            </footer>
        </main>

    </div>
</div>

</x-admin-layout>