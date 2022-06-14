<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userService;

    public function __construct (UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get_users()
    {
        // try {
            return $this->userService->getAllUser();
        //     return response()->json([
        //         'success' => true,
        //         'list_user' => $users
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'msg' => $e->getMessage(),
        //     ]);
        // }
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

    public function update_user_avatar(Request $request)
    {
        try {
            $request->validate([
                'avatar_user' => 'required|image|mimes:png,jpg|max:2048',
            ]);
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
