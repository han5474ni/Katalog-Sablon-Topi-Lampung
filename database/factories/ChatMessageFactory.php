<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatConversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition()
    {
        return [
            'conversation_id' => ChatConversation::factory(),
            'sender_id' => User::factory(),
            'message' => $this->faker->paragraph(),
            'sender_type' => $this->faker->randomElement(['user', 'admin']),
        ];
    }
}
