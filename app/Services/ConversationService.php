<?php

namespace App\Services;

use App\Repositories\ConversationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConversationService
{
    protected $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function dataConversationRepository()
    {
        return $this->conversationRepository->getDataConversation();
    }

    public function getConversationsOfUserLogging(Request $request)
    {
        try {
            $getConversationsOfUser = DB::table('user_conversations')
                ->join('users', 'user_conversations.user_id', '=', 'users.id')
                ->join('conversations', 'user_conversations.conversation_id', '=', 'conversations.conversation_id')
                ->select('user_conversations.conversation_id', 'conversations.name_conversation')
                ->where('users.id', $request->user()->id)
                ->get();
            return response()->json([
                'data' => $getConversationsOfUser,
                'user' => $request->user(),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::channel('custom')->info($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function getMsgInConversation(Request $request)
    {
        try {
            $conversation = $this->dataConversationRepository()->find($request->conversation_id);
            if ($conversation) {
                $getMessagesInConversation = DB::table('messages')
                    ->join('conversations', 'messages.cvs_id', '=', 'conversations.conversation_id')
                    ->join('users', 'messages.user_id', '=', 'users.id')
                    ->select("*")
                    ->where('conversations.conversation_id', $request->conversation_id)
                    ->get();
                $listInfo = [];
                foreach ($getMessagesInConversation as $info) {
                    $data = (object) [
                        'content' => $info->message,
                        'seender' => (object)[
                            'id' => $info->user_id,
                            'name' => $info->name
                        ],
                        'created_at' => $info->created_at
                    ];
                    array_push($listInfo, $data);
                }

                return response()->json([
                    'success' => true,
                    'conversation_id' => $request->conversation_id,
                    'data' =>  $listInfo,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'can not found conversation'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function getDataConversation(Request $request)
    {
        try {
            $conversation = $this->dataConversationRepository()->find($request->conversation_id);
            if ($conversation) {
                $getMessagesInConversation = DB::table('messages')
                    ->join('conversations', 'messages.cvs_id', '=', 'conversations.conversation_id')
                    ->join('users', 'messages.user_id', '=', 'users.id')
                    ->select("messages.messages_id", "messages.user_id", "messages.message", "messages.created_at")
                    ->where('conversations.conversation_id', $request->conversation_id)
                    ->get();

                $getUserInConversation = DB::table('messages')
                    ->select('users.*')
                    ->join('conversations', 'conversations.conversation_id', '=', 'messages.cvs_id')
                    ->join('users', 'users.id', '=', 'messages.user_id')
                    ->where('conversations.conversation_id', $request->conversation_id)
                    ->distinct()
                    ->get();

                return response()->json([
                    'success' => true,
                    'conversation_id' => $request->conversation_id,
                    'message' => $getMessagesInConversation,
                    'member' => $getUserInConversation
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'can not found conversation'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function joinConversation(Request $request)
    {
        try {
            $findConversation = $this->dataConversationRepository()->where('conversation_id', $request->conversation_id)->first();
            if ($findConversation) {
                $infoConversation = $this->dataConversationRepository()->where('conversation_id', $request->conversation_id)->first();
                $listConversation = DB::table('user_conversations')
                ->select('conversation_id')
                ->where('user_id', $request->user()->id)
                ->get();

                $listIdConversation = [];
                foreach ($listConversation as $id_room) {
                    array_push($listIdConversation, $id_room->conversation_id);
                }
                if (in_array($request->conversation_id, $listIdConversation)) {
                    return response()->json([
                        'message' => 'User da o trong cuoc tro chuyen'
                    ]);
                } else {
                    $this->conversationRepository->getDataUserConversation()->create([
                        'user_id' => $request->user()->id,
                        'conversation_id' => $request->conversation_id,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Join success',
                        'conversation_id' => $request->conversation_id,
                        'conversation' => $infoConversation->name_conversation,
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'message' => 'room does not exist' . $request->conversation_id
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function createConversation(Request $request)
    {
        try {
            $findUser = $this->conversationRepository->getDataUser()->where('id', $request->user_id)->first();
            if ($findUser && $request->user()->id != $request->user_id) {
                $this->dataConversationRepository()->create([
                    'name_conversation' => $request->name_conversation,
                    'avatar_conversation' => $request->avatar_conversation
                ]);

                $conversationCreate = $this->dataConversationRepository()->latest()->first();

                $this->conversationRepository->getDataUserConversation()->create([
                    'user_id' => $request->user()->id,
                    'conversation_id' => $conversationCreate->conversation_id
                ]);

                $this->conversationRepository->getDataUserConversation()->create([
                    'user_id' => $request->user_id,
                    'conversation_id' => $conversationCreate->conversation_id
                ]);

                return response()->json([
                    'success'  => true,
                    'message' => 'create successful',
                    'conversation_id' => $conversationCreate->conversation_id,
                    'list_user' => [
                        $request->user(),
                        $findUser,
                    ]
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'message' => 'Invalid User'
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function addUserToConversation(Request $request)
    {
        try {
            $findUser = $this->conversationRepository->getDataUser()->where('id', $request->user_id)->first();
            $findConversation = $this->dataConversationRepository()->where('conversation_id', $request->conversation_id)->first();
            if ($findConversation && $findUser) {
                $listConversation = DB::table('user_conversations')
                ->select('conversation_id')
                ->where('user_id', $request->user_id)
                ->get();
                $listIdConversation = [];
                foreach ($listConversation as $id_room) {
                    array_push($listIdConversation, $id_room->conversation_id);
                }
                if (in_array($request->conversation_id, $listIdConversation)) {
                    return response()->json([
                        'message' => 'User in conversation'
                    ]);
                } else {
                    $this->conversationRepository->getDataUserConversation()->create([
                        'user_id' => $request->user_id,
                        'conversation_id' => $request->conversation_id,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'add user success ' . $request->user_id,
                        'conversation_id' => $request->conversation_id,
                        'conversation' => $findConversation->name_conversation,
                        'user' => $findUser->name
                    ], Response::HTTP_CREATED);
                }
            } else {
                return response()->json([
                    'message' => 'Room or User does not exist'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function leaveConversation(Request $request)
    {
        try {
            $findConversation = $this->dataConversationRepository()->where('conversation_id', $request->conversation_id)->first();
            $dataDelete = $this->conversationRepository->getDataUserConversation()->where([
                'user_id' => $request->user()->id,
                'conversation_id' => $request->conversation_id
            ])->first();
            if ($dataDelete) {
                $dataDelete->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Leave room ' . $request->conversation_id . ' success',
                    'conversation_name' => $findConversation->name_conversation
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'User khong co trong phong'
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function removeFromConversation(Request $request)
    {
        try {
            $findConversation = $this->dataConversationRepository()->where('conversation_id', $request->conversation_id)->first();
            $listUser = $request->listUser;
            foreach ($listUser as $item => $value) {
                $dataDelete = $this->conversationRepository->getDataUserConversation()->where([
                    'user_id' => $value,
                    'conversation_id' => $request->conversation_id
                ])->first();
                $dataDelete->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Delete successful',
                'conversation_name' => $findConversation->name_conversation,
                'conversation_id' => $findConversation->conversation_id,
                'listUser' => $listUser,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function changeConversationName(Request $request)
    {
        try {
            $conversation = $this->dataConversationRepository()->find($request->id);
            if ($conversation) {
                $this->dataConversationRepository()->find($request->id)->update([
                    'name_conversation' => $request->name
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Rename successful',
                    'conversation_name' => $request->name,
                    'conversation_id' => $conversation->conversation_id,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Not found conversation'
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar_conversation' => 'required|image|mimes:png,jpg|max:2048',
            ]);
            $conversationUpdate = $this->dataConversationRepository()->find($request->conversation_id);
            $conversationUpdate->avatar_conversation = Storage::path(Storage::putFile('avatar_conversation', $request->file('avatar_conversation')));
            $conversationUpdate->save();
            return response()->json([
                'success' => true,
                'message' => 'Change image successful',
                'conversation_id' => $request->conversation_id,
                'conversation_avatar' => $conversationUpdate->avatar_conversation,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }

    public function deleteConversation(Request $request)
    {
        try {
            if ($this->dataConversationRepository()->find($request->id)) {
                $this->dataConversationRepository()->find($request->id)->delete();

                return response()->json([
                    'success' => true,
                    'id' => $request->id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'can not found id ' . $request->id
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error',
            ]);
        }
    }
}
