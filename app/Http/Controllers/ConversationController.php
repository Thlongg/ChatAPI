<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{

    protected $conversation;

    public function __construct(Conversation $conversations)
    {
        $this->conversation = $conversations;
    }

    public function get_coversations(Request $request)
    {

        $user = $request->user()->conversations();
        $getUserConversations = DB::select(DB::raw('SELECT user_conversation.conversation_id,conversations.name_conversation  
        FROM user_conversation,users,conversations 
        where user_conversation.conversation_id = conversations.conversation_id
        and users.id = user_conversation.user_id and users.id = ' . $request->id));

        return response()->json([
            'data' => $getUserConversations,
            'user' => $request->user()
        ], Response::HTTP_OK);
    }

    public function get_messages(Request $request)
    {
        $getUserConversations = DB::select(DB::raw('SELECT messages.message, messages.created_at, messages.user_id  
        FROM users,conversations,messages
        where messages.cvs_id = conversations.conversation_id
        and users.id = messages.user_id and conversations.conversation_id = ' . $request->id));

        return response()->json([
            'success' => true,
            'conversation_id' => $request->id,
            'data' => $getUserConversations,
        ], Response::HTTP_OK);
    }

    public function delete_conversation(Request $request)
    {
        $conversation = Conversation::find($request->id);
        $conversation->delete();

        return response()->json([
            'success' =>true,
            'id'=>$request->id
        ]);
    }
}
