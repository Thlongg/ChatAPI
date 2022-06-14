<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send_message(Request $request)
    {
        try {
            Message::create([
                'message' => $request->message,
                'user_id' => $request->user()->id,
                'cvs_id'  => $request->conversation_id
            ]);

            $messageCreate = Message::latest('created_at')->first();
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
