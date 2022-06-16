<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser()
    {
        try {
            $users = $this->userRepository->getDataUser()->all();
            return response()->json([
                'success' => true,
                'list_user' => $users
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $listSearch = $this->userRepository->getDataUser()->name($request->name)->get();
            return response()->json([
                'success' => true,
                'list_user' => $listSearch
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function change_name(Request $request)
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
                'status' => Response::HTTP_OK
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function change_image(Request $request)
    {
        try {
            $request->validate([
                'avatar_user' => 'required|image|mimes:png,jpg|max:2048',
            ]);
            $request->user()->avatar_user = Storage::path(Storage::putFile('images', $request->file('avatar_user')));
            ;
            $request->user()->save();

            return response()->json([
                'success' => true,
                'message' => 'Change image successful',
                'user_id' => $request->user()->id,
                'user_avatar' => $request->user()->avatar_user,
                'status' =>Response::HTTP_OK
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
