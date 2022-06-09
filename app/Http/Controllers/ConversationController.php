<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Models\UserConversation;
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

    public function get_user_login_coversations(Request $request)
    {

        $user = $request->user()->conversations();
        // dd($user);
        $getUserConversations = DB::select(DB::raw('SELECT user_conversation.conversation_id,conversations.name_conversation  
        FROM user_conversation,users,conversations 
        where user_conversation.conversation_id = conversations.conversation_id
        and users.id = user_conversation.user_id and users.id = ' . $request->user()->id));

        return response()->json([
            'data' => $getUserConversations,
            'user' => $request->user()
        ], Response::HTTP_OK);
    }

    public function get_messages_in_conversation(Request $request)
    {
        $conversation = Conversation::find($request->conversation_id);
        if ($conversation) {
            $getUserConversations = DB::select(DB::raw('SELECT messages.message, messages.created_at, messages.user_id  
            FROM users,conversations,messages
            where messages.cvs_id = conversations.conversation_id
            and users.id = messages.user_id and conversations.conversation_id = ' . $request->conversation_id));

            return response()->json([
                'success' => true,
                'conversation_id' => $request->conversation_id,
                'data' => $getUserConversations,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'can not found conversation'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function get_data_conversation(Request $request)
    {
        $conversation = Conversation::find($request->conversation_id);
        if ($conversation) {
            $getMsgConversations = DB::select(DB::raw('SELECT messages.messages_id,messages.user_id, messages.message, messages.created_at
            FROM users,conversations,messages
            where messages.cvs_id = conversations.conversation_id
            and users.id = messages.user_id and conversations.conversation_id = ' . $request->conversation_id));

            $gerUserInConversation = DB::select(DB::raw('SELECT DISTINCT users.*  
            FROM users,conversations,messages
            where messages.cvs_id = conversations.conversation_id
            and users.id = messages.user_id and conversations.conversation_id = ' . $request->conversation_id));
            //Có thể tìm qua bảng trung gian giữa user và conversation

            // dd($gerUserInConversation);
            return response()->json([
                'success' => true,
                'conversation_id' => $request->conversation_id,
                'message' => $getMsgConversations,
                'member' => $gerUserInConversation
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'can not found conversation'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function join_conversation(Request $request)
    {
        $findConversation = Conversation::where('conversation_id', $request->conversation_id)->first();
        if ($findConversation) {
            $infoConversation = Conversation::where('conversation_id', $request->conversation_id)->first();
            $listConversation = DB::select(DB::raw('SELECT conversation_id FROM user_conversation where user_id =' . $request->user()->id));
            $listIdConversation = [];
            foreach ($listConversation as $id_room) {
                array_push($listIdConversation, $id_room->conversation_id);
            }
            if (in_array($request->conversation_id, $listIdConversation)) {
                return response()->json([
                    'message' => 'User da o trong cuoc tro chuyen'
                ]);
            } else {
                UserConversation::create([
                    'user_id' => $request->user()->id,
                    'conversation_id' => $request->conversation_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Join thanh cong',
                    'conversation_id' => $request->conversation_id,
                    'conversation' => $infoConversation->name_conversation,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Khong ton tai phong ' . $request->conversation_id
            ]);
        }
    }

    public function add_user_to_conversation(Request $request)
    {
        $findUser = User::where('id', $request->user_id)->first();
        $findConversation = Conversation::where('conversation_id', $request->conversation_id)->first();
        if ($findConversation && $findUser) {
            $listConversation = DB::select(DB::raw('SELECT conversation_id FROM user_conversation where user_id =' . $request->user_id));
            $listIdConversation = [];
            foreach ($listConversation as $id_room) {
                array_push($listIdConversation, $id_room->conversation_id);
            }
            if (in_array($request->conversation_id, $listIdConversation)) {
                return response()->json([
                    'message' => 'User da o trong cuoc tro chuyen'
                ]);
            } else {
                UserConversation::create([
                    'user_id' => $request->user_id,
                    'conversation_id' => $request->conversation_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'them thanh cong user ' . $request->user_id,
                    'conversation_id' => $request->conversation_id,
                    'conversation' => $findConversation->name_conversation,
                    'user' => $findUser->name
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Khong ton tai phong hoac user'
            ]);
        }
    }

    public function leave_conversation(Request $request)
    {
        $findConversation = Conversation::where('conversation_id', $request->conversation_id)->first();
        $dataDelete = UserConversation::where([
            'user_id' => $request->user()->id,
            'conversation_id' => $request->conversation_id
        ])->first();
        if ($dataDelete) {
            $dataDelete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Roi phong ' . $request->conversation_id . ' thanh cong',
                'conversation_name' => $findConversation->name_conversation
            ]);
        } else {
            return response()->json([
                'message' => 'User khong co trong phong'
            ]);
        }
    }

    public function remove_from_conversation(Request $request)
    {
        $findConversation = Conversation::where('conversation_id', $request->conversation_id)->first();
        $listUser = $request->listUser;
        foreach ($listUser as $item => $value) {
            $dataDelete = UserConversation::where([
                'user_id' => $value,
                'conversation_id' => $request->conversation_id
            ])->first();
            $dataDelete->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Xoa thanh cong',
            'conversation_name' => $findConversation->name_conversation,
            'conversation_id' => $findConversation->conversation_id,
            'listUser' => $listUser
        ]);
    }

    public function change_conversation_name(Request $request)
    {
        $conversation = Conversation::find($request->id);
        if ($conversation) {
            Conversation::find($request->id)->update([
                'name_conversation' => $request->name
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Doi ten thanh cong',
                'conversation_name' => $request->name,
                'conversation_id' => $conversation->conversation_id,
            ]);
        }else
        {
            return response()->json([
                'message' => 'Khong tim thay phong'
            ]);
        }
    }

    public function delete_conversation(Request $request)
    {
        $conversation = Conversation::find($request->id);
        $conversation->delete();

        return response()->json([
            'success' => true,
            'id' => $request->id
        ]);
    }

}
