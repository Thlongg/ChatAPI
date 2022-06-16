<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $primaryKey = 'messages_id';

    protected $fillable = [ 'user_id', 'cvs_id','message'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function conversations()
    {
        return $this->belongsTo(Conversation::class, 'conversations', 'cvs_id', 'conversation_id');
    }
}
