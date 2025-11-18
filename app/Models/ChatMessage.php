<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'message',
        'is_escalted',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_escalted' => 'boolean'
    ];

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class);
    }
}