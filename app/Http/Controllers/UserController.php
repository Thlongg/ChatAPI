<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_users()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'list_user' => $users
        ]);
    }

    public function search_by_name(Request $request)
    {
        $a = User::name($request->name)->get();
        return response()->json([
            'success' => true,
            'list_user' => $a
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function change_user_name(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            User::find($request->id)->update([
                'name' => $request->name
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Doi ten thanh cong',
                'user_name' => $request->name,
                'user_id' => $user->id,
            ]);
        }else
        {
            return response()->json([
                'message' => 'Khong tim thay user'
            ]);
        }
    }
}
