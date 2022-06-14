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

    public function send_message(Request $request)
    {
        return $this->messageService->send_msg($request);
    }
}
