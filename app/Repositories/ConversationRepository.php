<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\UserConversation;

class ConversationRepository
{
    protected $conversation, $userConversation,$message, $user;
    public function __construct(
        Conversation $conversation,
        UserConversation $userConversation,
        Message $message, 
        User $user
    )
    {
        $this->conversation = $conversation;
        $this->userConversation = $userConversation;
        $this->message = $message;
        $this->user = $user;
    }

    public function getDataConversation()
    {
        return $this->conversation;
    }

    public function getDataUserConversation()
    {
        return $this->userConversation;
    }

    public function getDataMessage()
    {
        return $this->message;
    }

    public function getDataUser()
    {
        return $this->user;
    }
}
