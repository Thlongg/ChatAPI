<?php

namespace App\Services;

use App\Events\SendMSG;
use App\Repositories\MessageRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function getAllMessages()
    {
        return $this->messageRepository->getDataMessage()->all();
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
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function send(Request $request)
    {
        $message = $this->messageRepository->getDataMessage()->create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'cvs_id'  => $request->conversation_id
        ]);

        event(
            $e = new SendMSG($message)
        );

        return redirect()->route('msg.send');
    }
}
