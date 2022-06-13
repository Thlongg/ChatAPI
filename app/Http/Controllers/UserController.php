<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
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
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'list_user' => $users
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function search_by_name(Request $request)
    {
        try {
            $a = User::name($request->name)->get();
            return response()->json([
                'success' => true,
                'list_user' => $a
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
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
        try {
            // Storage::path(Storage::putFile('images', $request->file('avatar_user')));
            $request->user()->avatar_user = Storage::path(Storage::putFile('images', $request->file('avatar_user')));;
            $request->user()->save();

            return response()->json([
                'success' => true,
                'message' => 'Change image successful',
                'user_id' => $request->user()->id,
                'user_avatar' => $request->user()->avatar_user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
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
        try {
            $request->user()->update([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rename successful',
                'user_name' => $request->user()->name,
                'user_id' => $request->user()->id,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
