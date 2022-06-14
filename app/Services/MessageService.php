<?php

namespace App\Services;

use App\Repositories\MessageRepository;
use Exception;
use Illuminate\Http\Request;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function send_msg(Request $request)
    {
        try {
            $this->messageRepository->getDataMessage()->create([
                'message' => $request->message,
                'user_id' => $request->user()->id,
                'cvs_id'  => $request->conversation_id
            ]);

            $messageCreate = $this->messageRepository->getDataMessage()->latest('created_at')->first();
            return response()->json([
                'success' => true,
                'message' => $messageCreate->message,
                'timestamp' => $messageCreate->created_at,
                'conversation_id' => $messageCreate->cvs_id,
                'sender' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
