<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    protected  $primaryKey = 'conversation_id';

    protected $fillable = ['id_admin', 'id_room','name_conversation', 'avatar'];

    public function users()
    {
        return $this->belongsToMany(UserConversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'messages','conversation_id','cvs_id');
    }
}
