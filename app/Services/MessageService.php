<?php

namespace App\Services;

use App\Events\SendMSG;
use App\Repositories\MessageRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function getAllMessages($id)
    {
        $getMessagesInConversation =
            $this->messageRepository->getDataMessage()->join('conversations', 'messages.cvs_id', '=', 'conversations.conversation_id')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->select("messages.messages_id", "messages.user_id", "messages.message", "messages.created_at")
            ->where('conversations.conversation_id', $id)->oldest('created_at')
            ->get();

        return $getMessagesInConversation;
    }

    public function sendMsg(Request $request)
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
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function send(Request $request, $id)
    {
        $message = $this->messageRepository->getDataMessage()->create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'cvs_id'  => $request->id
        ]);

        return redirect()->route('msg.index', $id);
    }
}
