<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\MessageService;
use Exception;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function sendMessage(Request $request)
    {
        return $this->messageService->sendMsg($request);
    }

    public function index($id)
    {
        $messages = $this->messageService->getAllMessages($id);
        return view('chat', compact('messages', 'id'));
    }

    public function send(Request $request, $id)
    {
        return $this->messageService->send($request, $id);
    }
}
