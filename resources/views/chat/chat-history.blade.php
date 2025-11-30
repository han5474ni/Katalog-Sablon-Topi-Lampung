@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Chat History Header -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Chat History</h1>
                <p class="text-gray-600">View your conversation history with our support team</p>
            </div>
        </div>

        <!-- Chat Conversations List -->
        <div class="bg-white rounded-lg shadow">
            <div class="divide-y">
                @forelse($conversations ?? [] as $conversation)
                    <div class="p-6 hover:bg-gray-50 cursor-pointer transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $conversation['subject'] ?? 'Conversation' }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Last message: {{ $conversation['last_message_date'] ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                    {{ $conversation['last_message'] ?? 'No messages' }}
                                </p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 whitespace-nowrap ml-4">
                                {{ $conversation['status'] ?? 'active' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No conversations yet</h3>
                        <p class="text-gray-500">Start a chat with our support team to see the history here</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- New Chat Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('chatbot') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Start New Chat
            </a>
        </div>
    </div>
</div>
@endsection
