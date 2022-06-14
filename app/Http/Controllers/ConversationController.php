<?php

namespace App\Http\Controllers;

use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function get_user_login_coversations(Request $request)
    {
        return $this->conversationService->getUserInConversations($request);
    }

    public function get_messages_in_conversation(Request $request)
    {
        return $this->conversationService->getMsgInConversation($request);
    }

    public function get_data_conversation(Request $request)
    {
        return $this->conversationService->getDataConversation($request);
    }

    public function join_conversation(Request $request)
    {
        return $this->conversationService->joinConversation($request);
    }

    public function create_conversation(Request $request)
    {
        return $this->conversationService->createConversation($request);
    }

    public function add_user_to_conversation(Request $request)
    {
        return $this->conversationService->addUserToConversation($request);
    }

    public function leave_conversation(Request $request)
    {
        return $this->conversationService->leaveConversation($request);
    }

    public function remove_from_conversation(Request $request)
    {
        return $this->conversationService->removeFromConversation($request);
    }

    public function change_conversation_name(Request $request)
    {
        return $this->conversationService->changeConversationName($request);
    }

    public function update_conversation_avatar(Request $request)
    {
        return $this->conversationService->updateAvatar($request);
    }

    public function delete_conversation(Request $request)
    {
        return $this->conversationService->deleteConversation($request);
    }
}
