<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_message(Request $request)
    {
        Message::create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'cvs_id'  => $request->conversation_id
        ]);

        $a = Message::latest('created_at')->first();
        return response()->json([
            'success' => true,
            'message' => $request->message,
            'timestamp' => $a->created_at,
            'conversation_id' => $request->conversation_id,
            'sender' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
