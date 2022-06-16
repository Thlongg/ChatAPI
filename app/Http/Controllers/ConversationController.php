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

    public function getUserLoginCoversations(Request $request)
    {
        return $this->conversationService->getUserInConversations($request);
    }

    public function getMessagesInConversation(Request $request)
    {
        return $this->conversationService->getMsgInConversation($request);
    }

    public function getDataConversation(Request $request)
    {
        return $this->conversationService->getDataConversation($request);
    }

    public function joinConversation(Request $request)
    {
        return $this->conversationService->joinConversation($request);
    }

    public function createConversation(Request $request)
    {
        return $this->conversationService->createConversation($request);
    }

    public function addUserToConversation(Request $request)
    {
        return $this->conversationService->addUserToConversation($request);
    }

    public function leaveConversation(Request $request)
    {
        return $this->conversationService->leaveConversation($request);
    }

    public function removeFromConversation(Request $request)
    {
        return $this->conversationService->removeFromConversation($request);
    }

    public function changeConversationName(Request $request)
    {
        return $this->conversationService->changeConversationName($request);
    }

    public function updateConversationAvatar(Request $request)
    {
        return $this->conversationService->updateAvatar($request);
    }

    public function deleteConversation(Request $request)
    {
        return $this->conversationService->deleteConversation($request);
    }
}
