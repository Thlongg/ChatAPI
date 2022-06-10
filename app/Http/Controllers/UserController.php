<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function update_user_avatar(Request $request)
    {
        // dd($request->avatar_user);
        // $request->user()->update([
        //     'avatar_user' => $request->avatar_user,
        // $request->user()->save()
        // ]);
        $request->user()->avatar_user = $request->avatar_user;
        // $request->file('avatar_user')->store('avatar_user');
        $request->user()->save();

        // Storage::putFile('images', $request->file('avatar_user'));

        return response()->json([
            'success' => true,
            'message' => 'Change image successful',
            'user_id' => $request->user()->id,
            'user_avatar' => $request->user()->avatar_user,
        ]);
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
                'message' => 'Rename successful',
                'user_name' => $request->name,
                'user_id' => $user->id,
            ]);
        }else
        {
            return response()->json([
                'message' => 'user does not exist',
            ]);
        }
    }
}
