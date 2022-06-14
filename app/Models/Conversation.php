<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    protected $primaryKey = 'conversation_id';

    protected $fillable = ['admin_id', 'id_room','name_conversation', 'avatar_conversation'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_conversations', 'conversation_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'messages', 'conversation_id', 'cvs_id');
    }
}
